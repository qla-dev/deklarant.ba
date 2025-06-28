<?php

namespace Tests\Feature;

use App\Jobs\ProcessPdfToImages;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\UploadController;
use Tests\TestCase;

class UploadControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_file_upload()
    {
        Storage::fake('local');
        \Illuminate\Support\Facades\Queue::fake();

        $file = UploadedFile::fake()->create('declaration.pdf', 1024);

        $response = $this->postJson('/api/upload', [
            'file' => $file
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'task_id'
            ]);

        $this->assertDatabaseHas('tasks', [
            'original_filename' => 'declaration.pdf',
            'status' => Task::STATUS_PENDING
        ]);

        \Illuminate\Support\Facades\Queue::assertPushed(ProcessPdfToImages::class);
    }

    public function test_invalid_file_upload()
    {
        $file = UploadedFile::fake()->create('document.txt', 1024);

        $response = $this->postJson('/api/upload', [
            'file' => $file
        ]);

        $response->assertStatus(422);
    }

    public function test_task_status_check()
    {
        $task = Task::factory()->create();
        $task->markAsCompleted([]);
        $task->refresh();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'processing_step',
                'completed_at',
                'error_message',
                'created_at',
                'updated_at'
            ]);
        $this->assertNotNull($response->json('completed_at'));
    }

    public function test_result_retrieval()
    {
        $task = Task::factory()->create([
            'status' => Task::STATUS_COMPLETED,
            'result' => ['items' => []]
        ]);

        $response = $this->getJson("/api/tasks/{$task->id}/result");

        $response->assertStatus(200)
            ->assertJson(['items' => []]);
    }

    public function test_pending_result_retrieval()
    {
        $task = Task::factory()->create([
            'status' => Task::STATUS_PENDING
        ]);

        $response = $this->getJson("/api/tasks/{$task->id}/result");

        $response->assertStatus(400);
    }

    public function test_update_old_tasks()
    {
        // Create a task that is older than 1 day with PENDING status
        $oldPendingTask = Task::factory()->create([
            'status' => Task::STATUS_PENDING,
            'created_at' => Carbon::now()->subDays(2)
        ]);

        // Create a task that is older than 1 day with PROCESSING status
        $oldProcessingTask = Task::factory()->create([
            'status' => Task::STATUS_PROCESSING,
            'created_at' => Carbon::now()->subDays(2)
        ]);

        // Create a task that is less than 1 day old (should not be updated)
        $recentTask = Task::factory()->create([
            'status' => Task::STATUS_PENDING,
            'created_at' => Carbon::now()->subHours(20) // Less than 1 day
        ]);

        // Call the updateOldTasks method directly
        UploadController::updateOldTasks();

        // Refresh tasks to get updated data from database
        $oldPendingTask->refresh();
        $oldProcessingTask->refresh();
        $recentTask->refresh();

        // Assert that old tasks are marked as FAILED with the correct error message
        $this->assertEquals(Task::STATUS_FAILED, $oldPendingTask->status);
        $this->assertStringContainsString('Task is expired', $oldPendingTask->error_message);

        $this->assertEquals(Task::STATUS_FAILED, $oldProcessingTask->status);
        $this->assertStringContainsString('Task is expired', $oldProcessingTask->error_message);

        // Assert that the recent task was not updated
        $this->assertNotEquals(Task::STATUS_FAILED, $recentTask->status);
    }
}
