<?php

namespace App\Jobs;

use App\Models\Task;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessUploadedFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Task $task)
    {
    }

    public function handle(Client $client = null)
    {
        try {
            $this->task->markAsProcessing();

            // Step 1: Convert to markdown
            $this->task->updateStepStatus('conversion', 'processing');
            $markdown = $this->convertToMarkdown($client);
            $this->task->updateStepStatus('conversion', 'completed');

            // Step 2: Extract data with LLM
            $this->task->updateStepStatus('extraction', 'processing');
            $items = $this->extractWithLLM($markdown, $client);
            $this->task->updateStepStatus('extraction', 'completed');

            // Step 3: Enrich with search API
            $this->task->updateStepStatus('enrichment', 'processing');
            $enrichedItems = $this->enrichWithSearchAPI($items, $client);
            $this->task->updateStepStatus('enrichment', 'completed');

            $this->task->markAsCompleted($enrichedItems);
        } catch (\Exception $e) {
            $this->task->markAsFailed($e->getMessage());
            throw $e;
        }
    }

    public function convertToMarkdown(Client $client = null): string
    {
        $markerUrl = getenv('MARKER_URL');

        if (empty($markerUrl)) {
            return $this->convertToMarkdownViaCli();
        }

        return $this->convertToMarkdownViaHttp($client);
    }

    protected function convertToMarkdownViaHttp(Client $client = null): string
    {
        $client = $client ?? new Client();
        $fileContent = Storage::get($this->task->file_path);

        $response = $client->post(getenv('MARKER_URL') . '/marker/upload', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => $fileContent,
                    'filename' => $this->task->original_filename
                ],
                [
                    'name' => 'output_format',
                    'contents' => 'markdown'
                ],
                [
                    'name' => 'force_ocr',
                    'contents' => 'true'
                ],
                [
                    'name' => 'paginate_output',
                    'contents' => 'false'
                ]
            ]
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);
        if (!isset($responseData['output'])) {
            throw new \RuntimeException('Invalid marker response format');
        }
        return $responseData['output'];
    }

    protected $processFactory;

    public function setProcessFactory(callable $factory): void
    {
        $this->processFactory = $factory;
    }

    protected function getProcessFactory(): callable
    {
        return $this->processFactory ?? function (array $command) {
            return new \Symfony\Component\Process\Process($command);
        };
    }

    protected function convertToMarkdownViaCli(): string
    {
        $filePath = Storage::path($this->task->file_path);
        $tempDir = sys_get_temp_dir();
        $command = [
            'marker_single',
            $filePath,
            '--output_format=markdown',
            '--output_dir=' . $tempDir,
            '--force_ocr'
        ];

        $process = ($this->getProcessFactory())($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Marker CLI failed: ' . $process->getErrorOutput());
        }

        // Get input filename without extension
        $inputFilename = pathinfo($filePath, PATHINFO_FILENAME);
        $outputDir = $tempDir . '/' . $inputFilename;
        $outputFile = $outputDir . '/' . $inputFilename . '.md';

        if (!file_exists($outputFile)) {
            throw new \RuntimeException('Marker output file not found: ' . $outputFile);
        }

        $output = file_get_contents($outputFile);

        // Clean up
        array_map('unlink', glob("$outputDir/*"));
        rmdir($outputDir);

        return $output;
    }

    private function extractWithLLM(string $markdown, Client $client = null): array
    {
        $client = $client ?? new Client();

        $response = $client->post(getenv('OLLAMA_URL') . '/api/generate', [
            'json' => [
                'model' => getenv('OLLAMA_MODEL'),
                'prompt' => file_get_contents(base_path("app/Jobs/prompt-markdown-to-json.txt")) .
                    "\n\nHere's the markdown:\n\n$markdown",
                // 'format' => 'json',
                'stream' => false,
                'temperature' => 0.1
            ]
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);
        // check if response data contains "error" key. If it does then raise exception with "message" key
        // if "message" key is unavailable then raise exception with generic message text
        if (isset($responseData['error'])) {
            throw new \Exception($responseData['error']);
        }
        $ollamaResponse = $responseData['response'] ?? '';
        print_r($ollamaResponse);
        // Ensure ollamaResponse is a string between ```json and ```
        if (preg_match('/```json(.*?)```/s', $ollamaResponse, $matches)) {
            $ollamaResponse = trim($matches[1]);
        } elseif (preg_match('/```(.*?)```/s', $ollamaResponse, $matches)) {
            $ollamaResponse = trim($matches[1]);
        }

        $parsedResponse = json_decode($ollamaResponse, true);

        if ($parsedResponse === null) {
            throw new \Exception('Unable to parse response. Full response: ' . $ollamaResponse);
        }

        return $parsedResponse['items'] ?? [];
    }

    private function enrichWithSearchAPI(array $items, Client $client = null): array
    {
        $client = $client ?? new Client();
        $enriched = [];

        foreach ($items as $item) {
            if (!isset($item['item_name'])) {
                continue;
            }

            try {
                $response = $client->get(getenv('SEARCH_API_URL'), [
                    'query' => ['query' => $item['item_name']]
                ]);

                $results = json_decode($response->getBody()->getContents(), true) ?: [];
                $item['detected_codes'] = $results;
                $enriched[] = $item;
            } catch (\Exception $e) {
                $item['detected_codes'] = [];
                $enriched[] = $item;
            }
        }

        return $enriched;
    }
}
