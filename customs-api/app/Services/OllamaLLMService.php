<?php

namespace App\Services;

use App\Interfaces\LLMCaller;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class OllamaLLMService implements LLMCaller
{
    protected ?Client $client = null;

    public function __construct(Client $client = null)
    {
        if ($client !== null) {
            $this->client = $client;
        }
    }

    /**
     * Call the Language Model API with a given prompt and optional images.
     *
     * @param string $prompt The input prompt for the language model.
     * @param array|null $images Optional images to include in the request.
     * @return ResponseInterface The response from the language model API.
     */
    public function callLLM(string $prompt, ?array $images = null): ResponseInterface
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

                $response = $this->client->post(getenv('OLLAMA_URL') . '/api/generate', $body);
                return $response;
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
