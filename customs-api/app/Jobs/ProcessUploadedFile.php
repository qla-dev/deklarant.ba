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

    private function convertToMarkdown(Client $client = null): string
    {
        $client = $client ?? new Client();
        $fileContent = Storage::get($this->task->file_path);

        $response = $client->post(env('MARKER_URL').'/convert', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => $fileContent,
                    'filename' => $this->task->original_filename
                ]
            ]
        ]);

        return $response->getBody()->getContents();
    }

    private function extractWithLLM(string $markdown, Client $client = null): array
    {
        $client = $client ?? new Client();
        
        $response = $client->post(env('OLLAMA_URL').'/api/generate', [
            'json' => [
                'model' => env('OLLAMA_MODEL'),
                'prompt' => "Convert this customs declaration to JSON format with fields: item_name, original_name, quantity, unit_price, currency. Here's the markdown:\n\n$markdown",
                'format' => 'json',
                'stream' => false
            ]
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);
        $parsedResponse = json_decode($responseData['response'] ?? '', true);
        
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
                $response = $client->get(env('SEARCH_API_URL'), [
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
