<?php

namespace App\Utils;

use Exception;

class LLMUtils
{
    public static function parseLLMResponse(string $llmResponse): array
    {
        // Ensure llmResponse is a string between ```json and ```
        if (preg_match('/```json(.*?)```/s', $llmResponse, $matches)) {
            $llmResponse = trim($matches[1]);
        } elseif (preg_match('/```(.*?)```/s', $llmResponse, $matches)) {
            $llmResponse = trim($matches[1]);
        }

        $parsedResponse = json_decode($llmResponse, true);

        if ($parsedResponse === null) {
            throw new Exception('Unable to parse response. Full response: ' . $llmResponse);
        }

        // Process all string values in ['items'] and replace "\n" with "-"
        if (isset($parsedResponse['items']) && is_array($parsedResponse['items'])) {
            foreach ($parsedResponse['items'] as &$item) {
                if (is_array($item)) {
                    foreach ($item as $key => $value) {
                        if (is_string($value)) {
                            $item[$key] = str_replace("\n", " - ", $value);
                        }
                    }
                }
            }
        }

        return $parsedResponse;
    }
}
