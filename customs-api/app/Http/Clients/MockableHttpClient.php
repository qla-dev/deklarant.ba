<?php

namespace App\Http\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Added for logging
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class MockableHttpClient extends Client
{
    protected string $responsesPath = 'storage/app/http_responses';

    public function request(string $method, $uri = '', array $options = []): ResponseInterface
    {
        Log::info("Requesting {$method} to {$uri}"); // Added logging

        if (!$this->shouldUseRealAi()) {
            $request = new \GuzzleHttp\Psr7\Request($method, $uri);
            return $this->getMockResponse($request);
        }
        // Modify options to not check SSL issuer certificates
        $options['verify'] = false;
        $response = parent::request($method, $uri, $options);
        $content = $response->getBody()->getContents();
        $request = new \GuzzleHttp\Psr7\Request($method, $uri);
        if (filter_var(getenv('SAVE_MOCK_RESPONSE', 'false'), FILTER_VALIDATE_BOOLEAN)) {
            $this->validateAndStoreResponse($request, $content);
        }
        Log::info("Received response from {$uri}", ['status' => $response->getStatusCode()]); // Added logging

        return new Response(
            $response->getStatusCode(),
            $response->getHeaders(),
            \GuzzleHttp\Psr7\Utils::streamFor($content)
        );
    }

    protected function shouldUseRealAi(): bool
    {
        $useRealAi = filter_var(getenv('USE_REAL_AI'), FILTER_VALIDATE_BOOLEAN);
        Log::debug("shouldUseRealAi: " . ($useRealAi ? 'true' : 'false')); // Added logging
        return $useRealAi;
    }

    protected function getMockResponse(RequestInterface $request): ResponseInterface
    {
        $responseFile = $this->getResponseFilename($request);
        $responsePath = storage_path("app/http_responses/{$responseFile}");

        if (!file_exists($responsePath)) {
            Log::error("No mock response available for {$request->getUri()}");
            throw new \RuntimeException("No mock response available for {$request->getUri()}");
        }

        $delayMs = (int) getenv('FAKE_AI_DELAY');
        if ($delayMs > 0) {
            usleep($delayMs * 1000);
            Log::debug("Delayed mock response by {$delayMs}ms for {$request->getUri()}");
        }

        $content = file_get_contents($responsePath);
        Log::debug("Returning mock response for {$request->getUri()}");

        $stream = \GuzzleHttp\Psr7\Utils::streamFor($content);
        return new Response(200, [], $stream);
    }

    protected function validateAndStoreResponse(RequestInterface $request, string $content): void
    {
        $responseFile = $this->getResponseFilename($request);
        $responsePath = storage_path("app/http_responses/{$responseFile}");

        if (!file_exists($responsePath)) {
            Log::debug("Storing new response for {$request->getUri()}"); // Added logging
            $this->storeResponse($responsePath, $content);
            return;
        }

        $currentContent = file_get_contents($responsePath);

        if (!$this->responseShapesMatch($currentContent, $content)) {
            Log::debug("Storing updated response for {$request->getUri()}"); // Added logging
            $this->storeResponse($responsePath, $content);
        }
    }

    protected function storeResponse(string $path, string $content): void
    {
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        file_put_contents($path, $content);
        Log::debug("Stored response at {$path}"); // Added logging
    }

    protected function responseShapesMatch(string $current, string $new): bool
    {
        $currentData = json_decode($current, true);
        $newData = json_decode($new, true);

        return static::compareDataShapes($currentData, $newData);
    }

    public static function compareDataShapes($a, $b): bool
    {
        $typeA = gettype($a);
        $typeB = gettype($b);

        if ($typeA !== $typeB) {
            return false;
        }

        if (!is_array($a)) {
            return true;
        }

        // Handle empty arrays
        if (empty($a) || empty($b)) {
            return true;
        }

        // Check if both arrays are associative or both are indexed
        $aAssoc = static::isAssoc($a);
        $bAssoc = static::isAssoc($b);
        if ($aAssoc !== $bAssoc) {
            return false;
        }

        if ($aAssoc) {
            // For associative arrays, check all keys match
            if (array_keys($a) !== array_keys($b)) {
                return false;
            }
            foreach ($a as $key => $value) {
                if (!static::compareDataShapes($value, $b[$key])) {
                    return false;
                }
            }
        } else {
            // For indexed arrays, just check first element type if arrays are non-empty
            if (count($a) > 0 && count($b) > 0) {
                return static::compareDataShapes($a[0], $b[0]);
            }
            return true;
        }

        return true;
    }

    protected static function isAssoc(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    public static function getResponseFilename(RequestInterface $request): string
    {
        $path = $request->getUri()->getPath();
        $method = strtolower($request->getMethod());
        $query = $request->getUri()->getQuery();

        $filename = str_replace('/', '_', trim($path, '/'));
        if ($query) {
            $filename .= '_' . md5($query);
        }

        return "{$method}_{$filename}.json";
    }
}
