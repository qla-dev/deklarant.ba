<?php

namespace Tests\Unit;

use App\Jobs\ProcessUploadedFile;
use App\Models\Task;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProcessUploadedFileTest extends TestCase
{
    use RefreshDatabase;

    public function test_file_processing_pipeline()
    {
        Storage::fake('local');
        Storage::disk('local')->put('uploads/test.pdf', 'test content');

        $task = Task::factory()->create([
            'file_path' => 'uploads/test.pdf',
            'original_filename' => 'test.pdf'
        ]);

        // Mock HTTP responses
        $mock = new MockHandler([
            // Marker response (multipart form data)
            new Response(200, [], json_encode($this->generateFakeMarkerResponse('# Markdown content'))),
            // Ollama response
            new Response(200, [], json_encode([
                'response' => json_encode([
                    'items' => [
                        [
                            'item_name' => 'Test Item',
                            'original_name' => 'TEST-001',
                            'quantity' => 1,
                            'unit_price' => 10.99,
                            'currency' => 'USD'
                        ]
                    ]
                ]),
                'done' => true
            ])),
            // Search API response
            new Response(200, [], json_encode([
                ['entry' => ['code' => 'HS123'], 'closeness' => 0.9]
            ]))
        ]);

        $client = new \GuzzleHttp\Client(['handler' => HandlerStack::create($mock)]);
        $job = new ProcessUploadedFile($task);
        $job->handle($client);

        $task->refresh();
        $this->assertEquals(Task::STATUS_COMPLETED, $task->status);
        $this->assertArrayHasKey('items', $task->result);
        $this->assertIsArray($task->result['items']);
        $this->assertCount(1, $task->result['items']);
        $this->assertEquals('Test Item', $task->result['items'][0]['item_name']);
        $this->assertArrayHasKey('detected_codes', $task->result['items'][0]);
    }

    public function test_marker_failure()
    {
        Storage::fake('local');
        Storage::disk('local')->put('uploads/test.pdf', 'test content');

        $task = Task::factory()->create([
            'file_path' => 'uploads/test.pdf',
            'original_filename' => 'test.pdf'
        ]);

        $mock = new MockHandler([
            new Response(500, [], json_encode([
                'success' => false,
                'error' => 'Marker service error'
            ]))
        ]);

        $client = new \GuzzleHttp\Client(['handler' => HandlerStack::create($mock)]);
        $job = new ProcessUploadedFile($task);

        try {
            $job->handle($client);
            $this->fail('Expected exception was not thrown');
        } catch (\Exception $e) {
            $task->refresh();
            $this->assertEquals(Task::STATUS_FAILED, $task->status);
            $this->assertStringContainsString('Marker service error', $task->error_message);
        }
    }

    public function test_ollama_failure()
    {
        Storage::fake('local');
        Storage::disk('local')->put('uploads/test.pdf', 'test content');

        $task = Task::factory()->create([
            'file_path' => 'uploads/test.pdf'
        ]);

        $mock = new MockHandler([
            new Response(200, [], json_encode($this->generateFakeMarkerResponse('# Markdown content'))),
            new Response(500, [], 'Ollama service error')
        ]);

        $client = new \GuzzleHttp\Client(['handler' => HandlerStack::create($mock)]);
        $job = new ProcessUploadedFile($task);

        try {
            $job->handle($client);
            $this->fail('Expected exception was not thrown');
        } catch (\Exception $e) {
            $task->refresh();
            $this->assertEquals(Task::STATUS_FAILED, $task->status);
            $this->assertStringContainsString('Ollama service error', $task->error_message);
        }
    }
}
