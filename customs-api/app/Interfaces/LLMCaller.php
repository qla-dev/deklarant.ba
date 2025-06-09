<?php

namespace App\Interfaces;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;

interface LLMCaller
{
    /**
     * Call the Language Model API with a given prompt and optional images.
     *
     * @param string $prompt The input prompt for the language model.
     * @param array|null $images Optional images to include in the request.
     * @return string The response from the language model API.
     */
    public function callLLM(Client $client, string $prompt, ?array $images = null): string;
}
