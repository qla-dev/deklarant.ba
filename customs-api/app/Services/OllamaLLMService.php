<?php

namespace App\Services;

use App\Interfaces\LLMCaller;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class OllamaLLMService implements LLMCaller
{
    /**
     * Call the Language Model API with a given prompt and optional images.
     */
    public function callLLM(Client $client, string $prompt, ?array $images = null): string
    {
        $maxRetries = 3;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $body = [
                    'json' => [
                        'model' => getenv('OLLAMA_MODEL'),
                        'prompt' => $prompt,
                        'stream' => false,
                        'options' => [
                            'temperature' => 0.1,
                            'num_predict' => 10000
                        ]
                    ]
                ];

                if ($images !== null) {
                    $body['json']['images'] = $images;
                }

                $response = $client->post(getenv('OLLAMA_URL') . '/api/generate', $body);
                $responseData = json_decode($response->getBody()->getContents(), true);
                // check if response data contains "error" key. If it does then raise exception with "message" key
                // if "message" key is unavailable then raise exception with generic message text
                if (isset($responseData['error'])) {
                    throw new Exception($responseData['error']);
                }
                return $responseData['response'];
            } catch (Exception $e) {
                Log::error('Error in LLM. Retrying: ' . $e->getMessage());
                if ($attempt === $maxRetries) {
                    throw new Exception('LLM service error: ' . $e->getMessage());
                }
                $delayMs = (int) getenv('HTTP_RETRY_DELAY', 2000);
                if ($delayMs > 0) {
                    usleep($delayMs * 1000);
                }
            }
        }

        throw new Exception('Failed to call LLM after ' . $maxRetries . ' attempts');
    }
}
