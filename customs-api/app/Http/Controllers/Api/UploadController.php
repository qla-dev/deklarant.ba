<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Jobs\ProcessUploadedFile;
use App\Jobs\ProcessPdfToImages;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Update old tasks that are still pending or processing after 1 day.
     */
    public static function updateOldTasks()
    {
        // Get the current time in local timezone
        $now = Carbon::now();

        // Find all tasks with status PENDING or PROCESSING older than 1 day
        Task::whereIn('status', [Task::STATUS_PENDING, Task::STATUS_PROCESSING])
            ->where('created_at', '<=', $now->subDay())
            ->get()
            ->each(function ($task) {
                // Mark as failed with expiration message
                $task->markAsFailed("Task is expired");
            });
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,xlsx,jpg,jpeg,png,xls|max:1024000000'
        ]);

        $allowPaidModels = $request->header('X-Allow-Paid-Models') === 'true';

        $file = $request->file('file');
        $path = $file->store('uploads');

        $task = Task::create([
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'user_id' => null,
            'status' => Task::STATUS_PENDING
        ]);

        // Dispatch processing job
        // Text based processor
        // ProcessUploadedFile::dispatch($task);
        // Image based processor
        ProcessPdfToImages::dispatch($task, $allowPaidModels);

        UploadController::updateOldTasks();

        return response()->json([
            'message' => 'File uploaded successfully',
            'task_id' => $task->id,
            'use_paid_models' => $allowPaidModels
        ], 201);
    }

    public function show(Task $task)
    {
        return response()->json([
            'status' => $task->status,
            'processing_step' => $task->processing_step,
            'completed_at' => $task->completed_at,
            'error_message' => $task->error_message,
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at
        ]);
    }

    public function result(Task $task)
    {
        if ($task->status !== Task::STATUS_COMPLETED) {
            return response()->json([
                'message' => 'Task not completed yet'
            ], 400);
        }

        return response()->json($task->result);
    }
}
