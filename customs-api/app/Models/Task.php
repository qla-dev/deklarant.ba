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
        'processing_step',
        'result',
        'error_message',
        'completed_at'
    ];

    protected $casts = [
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
            'result' => $result,
            'processing_step' => null,
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
}
