<?php

namespace App\Services;

use App\Interfaces\LLMCaller;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class OpenrouterLLMService implements LLMCaller
{
    public function callLLM(Client $client, string $prompt, ?array $images = null): string
    {
        $maxRetries = 3;
        $model = 'meta-llama/llama-4-maverick:free';

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $messageContent = [];

                // Add prompt text as part of the message
                $messageContent[] = [
                    'type' => 'text',
                    'text' => $prompt,
                ];

                // Add image(s) as base64-encoded URLs
                if (!empty($images)) {
                    foreach ($images as $base64Image) {
                        $messageContent[] = [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => 'data:image/png;base64,' . $base64Image,
                            ]
                        ];
                    }
                }

                $body = [
                    'json' => [
                        'model' => $model,
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => $messageContent
                            ]
                        ]
                    ]
                ];

                $headers = [
                    'Authorization' => 'Bearer ' . getenv('OPENROUTER_API_KEY'),
                    'Helicone-Auth' => 'Bearer ' . getenv('HELICONE_API_KEY'),
                    'Content-Type' => 'application/json',
                ];

                $response = $client->post('https://openrouter.helicone.ai/api/v1/chat/completions', [
                    'headers' => $headers,
                    'json' => $body['json'],
                ]);
                $responseData = json_decode($response->getBody()->getContents(), true);
                $responseText = '';
                if (is_array($responseData) && isset($responseData["choices"][0]["message"]["content"])) {
                    $responseText = $responseData["choices"][0]["message"]["content"];
                }
                if (substr_count($responseText, "```") < 2) {
                    $model = 'qwen/qwen2.5-vl-72b-instruct:free';
                    throw new \Exception("Response didn't contain ```");
                }
                return $responseText;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error in LLM. Retrying: ' . $e->getMessage());
                if ($attempt === $maxRetries) {
                    throw new \Exception('LLM service error: ' . $e->getMessage());
                }

                $delayMs = (int) getenv('HTTP_RETRY_DELAY', 2000);
                if ($delayMs > 0) {
                    usleep($delayMs * 1000);
                }
            }
        }
    }
}