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
        $this->baseUrl = config('services.ai_server.url');
    }

    public function uploadDocument(string $filePath, string $originalName, bool $canUsePaidModels = false): array|null
    {
        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'X-Allow-Paid-Models' => $canUsePaidModels ? 'true' : 'false',
            ])
            ->attach(
                'file',
                file_get_contents($filePath),
                $originalName
            )
            ->post("{$this->baseUrl}/api/upload");

        return $this->handleResponse($response);
    }

    public function getTaskStatus(string $taskId): array|null
    {
        $response = Http::withOptions(['verify' => false])->
            get("{$this->baseUrl}/api/tasks/{$taskId}");
        return $this->handleResponse($response);
    }

    public function getTaskResult(string $taskId): array|null
    {
        $response = Http::withOptions(['verify' => false])->
            get("{$this->baseUrl}/api/tasks/{$taskId}/result");
        return $this->handleResponse($response);
    }

    protected function handleResponse(Response $response): array|null
    {
        if ($response->status() == 404) {
            return null;
        }
        if ($response->status() < 200 || $response->status() >= 300) {
            throw new RuntimeException("API request failed with status: {$response->status()} - {$response->getBody()->getContents()}");
        }

        return $response->json() ?? throw new RuntimeException("Failed to parse JSON response");
    }
}
