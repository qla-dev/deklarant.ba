<?php

namespace App\Jobs;

use App\Models\Task;
use App\Http\Clients\MockableHttpClient;
use App\Interfaces\LLMCaller;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Psr\Http\Message\ResponseInterface;
use App\Utils\LLMUtils;

class ProcessUploadedFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ?Client $client = null;
    protected LLMCaller $llmCaller;

    public function __construct(public Task $task, protected bool $allowPaidModels = false, Client $client = null)
    {
        if ($client !== null) {
            $this->client = $client;
        }
    }

    public function handle(LLMCaller $llmCaller)
    {
        $this->llmCaller = $llmCaller;
        if ($this->client === null) {
            $this->client = new MockableHttpClient();
        }
        try {
            $this->task->markAsProcessing();

            // Step 1: Preprocess
            $this->task->update(['processing_step' => 'conversion']);
            $preprocessedData = $this->convertToLLM();

            // Step 2: Extract data with LLM
            $this->task->update(['processing_step' => 'extraction']);
            $result = $this->extractWithLLM($preprocessedData);

            // Step 3: Enrich with search API
            $this->task->update(['processing_step' => 'enrichment']);
            $enrichedItems = $this->enrichWithSearchAPI($result['items']);
            $result['items'] = $enrichedItems;

            $this->task->markAsCompleted($result);
        } catch (Exception $e) {
            $this->task->markAsFailed($e->getMessage());
            throw $e;
        }
    }

    public function convertToLLM()
    {
        $markerUrl = getenv('MARKER_URL');

        if (empty($markerUrl)) {
            return $this->convertToLLMViaCli();
        }

        return $this->convertToLLMViaHttp();
    }

    protected function convertToLLMViaHttp(): string
    {
        $fileContent = Storage::get($this->task->file_path);

        $response = $this->client->post(getenv('MARKER_URL') . '/marker/upload', [
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

        $responseBody = $response->getBody()->getContents();
        $responseData = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
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
            return new \Symfony\Component\Process\Process($command, timeout: null);
        };
    }

    protected function convertToLLMViaCli(): string
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

    protected function extractWithLLM($markdown): array
    {
        $responseData = $this->llmCaller->callLLM(
            $this->client,
            "Here's the markdown of invoice:\n\n```md\n$markdown\n```\n\n"
                . file_get_contents(base_path("app/Jobs/prompt-markdown-to-json.txt")),
            $this->allowPaidModels,
        );
        return LLMUtils::parseLLMResponse($responseData);
    }

    private function enrichWithSearchAPI(array $items): array
    {
        $enriched = [];

        foreach ($items as $item) {
            if (!isset($item['item_name'])) {
                continue;
            }

            $response = $this->client->get(getenv('SEARCH_API_URL'), [
                'query' => ['query' => $item['item_name']]
            ]);

            $results = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

            if (is_null($results))
                throw new Exception("unable to decode response from code search API");

            // Transform API response to expected format
            $item['detected_codes'] = $results;
            $enriched[] = $item;
        }

        return $enriched;
    }
}
