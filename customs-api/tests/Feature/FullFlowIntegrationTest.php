<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FullFlowIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_process_multipage_carparts_pdf()
    {
        Storage::fake('local');

        $file = new UploadedFile(
            base_path('tests/Fixtures/files/test-1-multipage-carparts.pdf'),
            'test-1-multipage-carparts.pdf',
            'application/pdf',
            null,
            true
        );

        $response = $this->postJson('/api/upload', [
            'file' => $file
        ]);

        $response->assertStatus(201);
        $taskId = $response->json('task_id');

        // Wait for processing to complete
        $this->waitForTaskCompletion($taskId);

        // Verify final results
        $resultResponse = $this->getJson("/api/tasks/{$taskId}/result");
        $resultResponse->assertStatus(200)
            ->assertJsonStructure([
                'items' => [
                    '*' => [
                        'item_name',
                        'original_name',
                        'quantity',
                        'unit_price',
                        'currency'
                    ]
                ]
            ]);
    }

    public function test_process_singlepage_no_ocr_pdf()
    {
        Storage::fake('local');

        $file = new UploadedFile(
            base_path('tests/Fixtures/files/test-2-singlepage-no-ocr.pdf'),
            'test-2-singlepage-no-ocr.pdf',
            'application/pdf',
            null,
            true
        );

        $response = $this->postJson('/api/upload', [
            'file' => $file
        ]);

        $response->assertStatus(201);
        $taskId = $response->json('task_id');

        // Wait for processing to complete
        $this->waitForTaskCompletion($taskId);

        // Verify final results
        $resultResponse = $this->getJson("/api/tasks/{$taskId}/result");
        $resultResponse->assertStatus(200)
            ->assertJsonStructure([
                'items' => [
                    '*' => [
                        'item_name',
                        'original_name',
                        'quantity',
                        'unit_price',
                        'currency'
                    ]
                ]
            ]);
    }

    public function test_process_singlepage_many_items_pdf()
    {
        Storage::fake('local');

        $file = new UploadedFile(
            base_path('tests/Fixtures/files/test-3-singlepage-many-items.pdf'),
            'test-3-singlepage-many-items.pdf',
            'application/pdf',
            null,
            true
        );

        $response = $this->postJson('/api/upload', [
            'file' => $file
        ]);

        $response->assertStatus(201);
        $taskId = $response->json('task_id');

        // Wait for processing to complete
        $this->waitForTaskCompletion($taskId);

        // Verify final results
        $resultResponse = $this->getJson("/api/tasks/{$taskId}/result");
        $resultResponse->assertStatus(200)
            ->assertJsonStructure([
                'items' => [
                    '*' => [
                        'item_name',
                        'original_name',
                        'quantity',
                        'unit_price',
                        'currency'
                    ]
                ]
            ]);
    }

    private function waitForTaskCompletion(string $taskId, int $timeout = 60): void
    {
        $start = time();

        while (time() - $start < $timeout) {
            $task = Task::find($taskId);

            if ($task->status === Task::STATUS_COMPLETED) {
                return;
            }

            if ($task->status === Task::STATUS_FAILED) {
                $this->fail('Task processing failed');
            }

            sleep(1);
        }

        $this->fail('Task processing timed out');
    }
}
