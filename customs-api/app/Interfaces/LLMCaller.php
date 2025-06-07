<?php

namespace App\Interfaces;

use Psr\Http\Message\ResponseInterface;

interface LLMCaller
{
    /**
     * Call the Language Model API with a given prompt and optional images.
     *
     * @param string $prompt The input prompt for the language model.
     * @param array|null $images Optional images to include in the request.
     * @return ResponseInterface The response from the language model API.
     */
    public function callLLM(string $prompt, ?array $images = null): ResponseInterface;
}
