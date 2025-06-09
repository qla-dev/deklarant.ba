<?php

namespace Tests\Unit;

use App\Jobs\ProcessUploadedFile;
use App\Models\Task;
use App\Interfaces\LLMCaller;
use App\Services\OllamaLLMService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProcessUploadedFileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        putenv('MARKER_URL=http://example.com');
        putenv('HTTP_RETRY_DELAY=0');
    }

    protected function createOllamaService(Client $client): OllamaLLMService
    {
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
        $ollamaService = $this->createOllamaService($client);
        $job = new ProcessUploadedFile($task, $ollamaService, $client);
        $job->handle();

        $task->refresh();
        $this->assertEquals(Task::STATUS_COMPLETED, $task->status);
        $this->assertArrayHasKey('items', $task->result);
        $this->assertIsArray($task->result['items']);
        $this->assertCount(1, $task->result['items']);
        $this->assertEquals('Test Item', $task->result['items'][0]['item_name']);
        $this->assertArrayHasKey('detected_codes', $task->result['items'][0]);
        $this->assertCount(1, $task->result['items'][0]['detected_codes']);
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
        $ollamaService = $this->createOllamaService($client);
        $job = new ProcessUploadedFile($task, $ollamaService, $client);

        try {
            $job->handle();
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
            new Response(200, [], json_encode(['output' => '# Markdown content'])),
            new Response(500, [], 'Ollama service error'),
            new Response(500, [], 'Ollama service error'),
            new Response(500, [], 'Ollama service error')
        ]);

        $client = new \GuzzleHttp\Client(['handler' => HandlerStack::create($mock)]);
        $ollamaService = $this->createOllamaService($client);
        $job = new ProcessUploadedFile($task, $ollamaService, $client);

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

        // First attempt fails, second attempt succeeds
        $mock = new MockHandler([
            new Response(200, [], json_encode(['output' => '# Markdown content'])),
            new Response(500, [], 'Ollama service error'),
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
        $ollamaService = $this->createOllamaService($client);
        $job = new ProcessUploadedFile($task, $ollamaService, $client);
        $job->handle();

        $task->refresh();
        $this->assertEquals(Task::STATUS_COMPLETED, $task->status);
        $this->assertArrayHasKey('items', $task->result);
        $this->assertIsArray($task->result['items']);
        $this->assertCount(1, $task->result['items']);
        $this->assertEquals('Test Item', $task->result['items'][0]['item_name']);
        $this->assertArrayHasKey('detected_codes', $task->result['items'][0]);
        $this->assertCount(1, $task->result['items'][0]['detected_codes']);
    }

    public function test_cli_path_via_invalid_url()
    {
        Storage::fake('local');
        $filePath = 'uploads/test.pdf';
        Storage::disk('local')->put($filePath, 'test content');
        putenv('MARKER_URL=');

        $task = Task::factory()->create([
            'file_path' => $filePath,
            'original_filename' => 'test.pdf'
        ]);

        // Create a mock process that simulates success
        $mockProcess = $this->createMock(\Symfony\Component\Process\Process::class);
        $mockProcess->method('isSuccessful')->willReturn(true);
        $mockProcess->method('run')->willReturn(0);

        // Set up the process factory
        $client = new \GuzzleHttp\Client();
        $ollamaService = $this->createOllamaService($client);
        $job = new ProcessUploadedFile($task, null, $ollamaService);
        $job->setProcessFactory(function ($command) use ($mockProcess) {
            $this->assertContains('marker_single', $command);
            $this->assertContains('--output_format=markdown', $command);
            return $mockProcess;
        });

        // Mock file operations for CLI path
        $tempDir = sys_get_temp_dir();
        $inputFilename = 'test'; // from test.pdf
        $outputDir = $tempDir . '/' . $inputFilename;
        $outputFile = $outputDir . '/' . $inputFilename . '.md';

        // Create the expected directory structure
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }
        file_put_contents($outputFile, '# CLI Markdown content');

        $markdown = $job->convertToLLM();

        $this->assertEquals('# CLI Markdown content', $markdown);
        $this->assertEquals('test content', Storage::disk('local')->get($filePath));
    }

    public function test_cli_failure_via_invalid_url()
    {
        Storage::fake('local');
        Storage::disk('local')->put('uploads/test.pdf', 'test content');
        putenv('MARKER_URL=');

        $task = Task::factory()->create([
            'file_path' => 'uploads/test.pdf'
        ]);

        // Mock failed marker CLI command
        $mockProcess = $this->createMock(\Symfony\Component\Process\Process::class);
        $mockProcess->method('isSuccessful')->willReturn(false);
        $mockProcess->method('getErrorOutput')->willReturn('Marker CLI error');

        $job = $this->getMockBuilder(ProcessUploadedFile::class)
            ->setConstructorArgs([$task, null, $this->createOllamaService(new \GuzzleHttp\Client())])
            ->onlyMethods(['convertToLLMViaCli'])
            ->getMock();

        $job->expects($this->once())
            ->method('convertToLLMViaCli')
            ->willThrowException(new \RuntimeException('Marker CLI failed: Marker CLI error'));

        $job->setProcessFactory(function ($command) use ($mockProcess) {
            return $mockProcess;
        });

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Marker CLI failed: Marker CLI error');
        $job->convertToLLM();
    }

    public function test_http_path_via_valid_url()
    {
        Storage::fake('local');
        Storage::disk('local')->put('uploads/test.pdf', 'test content');

        $task = Task::factory()->create([
            'file_path' => 'uploads/test.pdf',
            'original_filename' => 'test.pdf'
        ]);

        $mock = new MockHandler([
            new Response(200, [], json_encode(['output' => '# HTTP Markdown content']))
        ]);

        putenv('MARKER_URL=http://example.com');
        $client = new \GuzzleHttp\Client(['handler' => HandlerStack::create($mock)]);
        $ollamaService = $this->createOllamaService($client);
        $job = new ProcessUploadedFile($task, $ollamaService, $client);
        $markdown = $job->convertToLLM();

        $this->assertEquals('# HTTP Markdown content', $markdown);
    }

    protected function generateFakeMarkerResponse(string $markdown): array
    {
        return [
            'output' => $markdown,
            'success' => true
        ];
    }
}
