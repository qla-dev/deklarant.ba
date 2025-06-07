<?php

namespace Tests\Unit;

use App\Jobs\ProcessPdfToImages;
use App\Jobs\ProcessUploadedFile;
use App\Models\Task;
use App\Services\OllamaLLMService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProcessPdfToImagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        putenv('HTTP_RETRY_DELAY=0');
    }

    protected function createOllamaService(?Client $client = null): OllamaLLMService
    {
        $client = $client ?? new Client();
        return new OllamaLLMService($client);
    }

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
            new Response(200, [], json_encode([
                ['entry' => ['code' => 'HS123'], 'closeness' => 0.9]
            ]))
        ]);

        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $job = $this->create_success_magick_factory($task, $client);
        $job->handle();

        $task->refresh();
        $this->assertEquals(Task::STATUS_COMPLETED, $task->status);
        $this->assertArrayHasKey('items', $task->result);
        $this->assertIsArray($task->result['items']);
        $this->assertCount(1, $task->result['items']);
        $this->assertEquals('Test Item', $task->result['items'][0]['item_name']);
        $this->assertArrayHasKey('detected_codes', $task->result['items'][0]);
    }

    public function test_ollama_failure()
    {
        Storage::fake('local');
        Storage::disk('local')->put('uploads/test.pdf', 'test content');

        $task = Task::factory()->create([
            'file_path' => 'uploads/test.pdf'
        ]);

        $mock = new MockHandler([
            new Response(500, [], 'Ollama service error'),
            new Response(500, [], 'Ollama service error'),
            new Response(500, [], 'Ollama service error'),
        ]);

        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $job = $this->create_success_magick_factory($task, $client);

        try {
            $job->handle();
            $this->fail('Expected exception was not thrown');
        } catch (\Exception $e) {
            $task->refresh();
            $this->assertEquals(Task::STATUS_FAILED, $task->status);
            $this->assertStringContainsString('Ollama service error', $task->error_message);
        }
    }

    public function test_ollama_retry_success()
    {
        Storage::fake('local');
        Storage::disk('local')->put('uploads/test.pdf', 'test content');

        $task = Task::factory()->create([
            'file_path' => 'uploads/test.pdf'
        ]);

        $mock = new MockHandler([
            new Response(500, [], 'Ollama service error'),
            new Response(200, [], json_encode([
                'response' => '```json'."\n".'{"items":[{"item_name":"Test Item"}]}'."\n".'```',
                'done' => true
            ])),
            new Response(200, [], json_encode([
                ['entry' => ['code' => 'HS123'], 'closeness' => 0.9]
            ]))
        ]);

        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $job = $this->create_success_magick_factory($task, $client);
        $job->handle();

        $task->refresh();
        $this->assertEquals(Task::STATUS_COMPLETED, $task->status);
        $this->assertArrayHasKey('items', $task->result);
        $this->assertCount(1, $task->result['items']);
    }

    public function test_image_conversion_converts_to_base64()
    {
        Storage::fake('local');
        $filePath = 'uploads/test.pdf';
        Storage::disk('local')->put($filePath, 'test content');

        $task = Task::factory()->create([
            'file_path' => $filePath,
            'original_filename' => 'test.pdf'
        ]);

        $client = new Client();
        $job = $this->create_success_magick_factory($task, $client);
        $images = $job->convertToLLM();

        $this->assertEquals(["VGVzdCBpbWFnZSBkYXRh"], $images);
    }

    public function test_magick_cli_failure()
    {
        Storage::fake('local');
        $filePath = 'uploads/test.pdf';
        Storage::disk('local')->put($filePath, 'test content');

        $task = Task::factory()->create([
            'file_path' => $filePath,
            'original_filename' => 'test.pdf'
        ]);

        $mockProcess = $this->createMock(\Symfony\Component\Process\Process::class);
        $mockProcess->method('isSuccessful')->willReturn(false);
        $mockProcess->method('getErrorOutput')->willReturn('Magick CLI error');

        $client = new Client();
        $job = new ProcessPdfToImages($task, $client, $this->createOllamaService($client));
        $job->setProcessFactory(function ($command) use ($mockProcess) {
            return $mockProcess;
        });

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Image Magick conversion failed: Magick CLI error');
        $job->convertToLLM();
    }

    private function create_success_magick_factory($task, $client = null)
    {
        $mockProcess = $this->createMock(\Symfony\Component\Process\Process::class);
        $mockProcess->method('isSuccessful')->willReturn(true);
        $mockProcess->method('run')->willReturn(0);

        $client = $client ?? new Client();
        $job = new ProcessPdfToImages($task, $client, $this->createOllamaService($client));
        $job->setProcessFactory(function ($command) use ($mockProcess) {
            $this->assertContains('magick', $command);
            $imagePathTemplate = end($command);
            $actualImagePath = str_replace('%d', '0', $imagePathTemplate);
            file_put_contents($actualImagePath, "Test image data");
            return $mockProcess;
        });

        return $job;
    }
}
