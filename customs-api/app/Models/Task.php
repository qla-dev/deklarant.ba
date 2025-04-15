<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_filename',
        'file_path',
        'status',
        'processing_steps',
        'result',
        'error_message'
    ];

    protected $casts = [
        'processing_steps' => 'array',
        'result' => 'array'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    public function markAsProcessing()
    {
        $this->update(['status' => self::STATUS_PROCESSING]);
    }

    public function markAsCompleted(array $result)
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'result' => ['items' => $result],
            'completed_at' => now()
        ]);
    }

    public function markAsFailed(string $error)
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error_message' => $error
        ]);
    }

    public function updateStepStatus(string $step, string $status)
    {
        $steps = $this->processing_steps ?? [];
        $steps[$step] = $status;
        $this->update(['processing_steps' => $steps]);
    }
}
