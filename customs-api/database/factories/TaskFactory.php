<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'original_filename' => $this->faker->word . '.pdf',
            'file_path' => 'uploads/' . $this->faker->uuid . '.pdf',
            'status' => Task::STATUS_PENDING,
            'processing_step' => null,
            'result' => null,
            'error_message' => null
        ];
    }

    public function completed()
    {
        return $this->state([
            'status' => Task::STATUS_COMPLETED,
            'result' => ['items' => []]
        ]);
    }

    public function failed()
    {
        return $this->state([
            'status' => Task::STATUS_FAILED,
            'error_message' => 'Processing failed'
        ]);
    }
}
