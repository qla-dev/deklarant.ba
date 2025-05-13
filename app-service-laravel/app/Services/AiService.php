<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use RuntimeException;

class AiService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('AI_SERVER_URL'), '/');
    }

    public function uploadDocument(string $filePath, string $originalName): array
    {
        $response = Http::attach(
            'file', 
            file_get_contents($filePath),
            $originalName
        )->post("{$this->baseUrl}/api/upload");

        return $this->handleResponse($response);
    }

    public function getTaskStatus(string $taskId): array
    {
        $response = Http::get("{$this->baseUrl}/api/tasks/{$taskId}");
        return $this->handleResponse($response);
    }

    public function getTaskResult(string $taskId): array
    {
        $response = Http::get("{$this->baseUrl}/api/tasks/{$taskId}/result");
        return $this->handleResponse($response);
    }

    protected function handleResponse(Response $response): array
    {
        if ($response->status() !== 200) {
            throw new RuntimeException("API request failed with status: {$response->status()}");
        }

        return $response->json() ?? throw new RuntimeException("Failed to parse JSON response");
    }
}
