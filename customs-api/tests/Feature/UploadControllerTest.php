<?php

namespace Tests\Feature;

use App\Jobs\ProcessUploadedFile;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

        \Illuminate\Support\Facades\Queue::assertPushed(ProcessUploadedFile::class);
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

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'processing_steps',
                'created_at',
                'updated_at'
            ]);
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
}
