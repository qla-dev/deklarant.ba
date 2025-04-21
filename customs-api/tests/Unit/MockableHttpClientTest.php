<?php

namespace Tests\Unit;

use App\Http\Clients\MockableHttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class MockableHttpClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        putenv('USE_REAL_AI=false');
        putenv('FAKE_AI_DELAY=0');

        // Ensure response directory exists
        $responseDir = storage_path('app/http_responses');
        if (!file_exists($responseDir)) {
            mkdir($responseDir, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        // Clean up only test files we created
        $testFiles = [
            'post_test_api.json',
            'get_test_api.json'
        ];

        foreach ($testFiles as $file) {
            $path = storage_path('app/http_responses/' . $file);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        parent::tearDown();
    }

    public function test_fake_mode_returns_mock_response()
    {
        // Create a test response file
        $responsePath = storage_path('app/http_responses/post_test_api.json');
        file_put_contents($responsePath, json_encode(['output' => 'test markdown']));

        $client = new MockableHttpClient();
        $request = new Request(
            'POST',
            'http://localhost:9123/test/api',
            [],
            'test content'
        );

        $response = $client->request($request->getMethod(), (string) $request->getUri());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['output' => 'test markdown'], json_decode($response->getBody(), true));

        // Clean up
        unlink($responsePath);
    }

    public function test_fake_mode_throws_when_no_mock_exists()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No mock response available');

        $client = new MockableHttpClient();
        $request = new Request(
            'POST',
            'http://localhost:9123/test/api',
            [],
            'test content'
        );

        $client->request($request->getMethod(), (string) $request->getUri());
    }

    public function test_real_mode_makes_correct_request()
    {
        putenv('USE_REAL_AI=true');

        $mock = new MockHandler([
            new Response(200, [], json_encode(['success' => true]))
        ]);

        $history = [];
        $historyMiddleware = Middleware::history($history);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($historyMiddleware);

        $client = new MockableHttpClient(['handler' => $handlerStack]);
        $request = new Request(
            'POST',
            'http://localhost:9123/test/api',
            ['Content-Type' => 'application/json'],
            json_encode(['test' => 'data'])
        );

        $response = $client->request($request->getMethod(), (string) $request->getUri(), [
            'json' => ['test' => 'data']
        ]);

        $this->assertCount(1, $history);
        $sentRequest = $history[0]['request'];
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/test/api', $sentRequest->getUri()->getPath());

        $body = (string) $sentRequest->getBody();
        $decodedBody = json_decode($body, true);
        $this->assertIsArray($decodedBody);
        $this->assertEquals(['test' => 'data'], $decodedBody);
    }

    public function test_compare_data_shapes_identical()
    {
        $data1 = [
            'name' => 'test',
            'items' => [
                ['id' => 1, 'value' => 5],
                ['id' => 2, 'value' => 'b']
            ]
        ];

        $data2 = [
            'name' => 'test',
            'items' => [
                ['id' => 3, 'value' => 6],
                ['id' => 4, 'value' => 'c']
            ]
        ];

        $this->assertTrue(MockableHttpClient::compareDataShapes($data1, $data2));
    }

    public function test_compare_data_shapes_identical_different_types()
    {
        $data1 = [
            'name' => 'test',
            'items' => [
                ['id' => 1, 'value' => 5], // This is string in $data2
                ['id' => 2, 'value' => 'b']
            ]
        ];

        $data2 = [
            'name' => 'different',
            'items' => [
                ['id' => 3, 'value' => 'c'], // This is number in $data1
                ['id' => 4, 'value' => 'd']
            ]
        ];

        $this->assertFalse(MockableHttpClient::compareDataShapes($data1, $data2));
    }

    public function test_compare_data_shapes_arrays_with_different_length()
    {
        $data1 = [
            'name' => 'test',
            'items' => [
                1,
                2,
                3
            ]
        ];

        $data2 = [
            'name' => 'different',
            'items' => [
                1,
                2
            ]
        ];

        $this->assertTrue(MockableHttpClient::compareDataShapes($data1, $data2));
    }

    public function test_compare_data_shapes_different_structure()
    {
        $data1 = [
            'name' => 'test',
            'items' => [1, 2, 3]
        ];

        $data2 = [
            'name' => 'test',
            'items' => ['a', 'b', 'c']
        ];

        $this->assertFalse(MockableHttpClient::compareDataShapes($data1, $data2));
    }

    public function test_compare_data_shapes_invalid()
    {
        $data1 = [
            'name' => 'test',
            'items' => [1, 2, 3]
        ];

        $data2 = [
            'name' => 'test',
            'items' => ['a' => 1, 'b' => 2]
        ];

        $this->assertFalse(MockableHttpClient::compareDataShapes($data1, $data2));
    }

    public function test_get_response_filename()
    {
        $request1 = new Request('GET', 'http://example.com/api/users');
        $filename1 = MockableHttpClient::getResponseFilename($request1);
        $this->assertEquals('get_api_users.json', $filename1);

        $request2 = new Request('POST', 'http://example.com/api/users/create');
        $filename2 = MockableHttpClient::getResponseFilename($request2);
        $this->assertEquals('post_api_users_create.json', $filename2);

        $request3 = new Request('PUT', 'http://example.com/api/users/123');
        $filename3 = MockableHttpClient::getResponseFilename($request3);
        $this->assertEquals('put_api_users_123.json', $filename3);
    }

    public function test_get_request_in_fake_mode()
    {
        // Create a test response file
        $responsePath = storage_path('app/http_responses/get_test_api.json');
        file_put_contents($responsePath, json_encode(['test' => 'data']));

        $client = new MockableHttpClient();
        $response = $client->get('http://localhost:9123/test/api');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            ['test' => 'data'],
            json_decode($response->getBody(), true)
        );

        // Test that getBody() can be called multiple times
        $body1 = $response->getBody()->getContents();
        $body2 = $response->getBody()->getContents();
        $this->assertEquals($body1, $body2);

        // Clean up
        unlink($responsePath);
    }

    public function test_get_request_in_real_mode()
    {
        putenv('USE_REAL_AI=true');

        $mock = new MockHandler([
            new Response(200, [], json_encode(['test' => 'data']))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new MockableHttpClient(['handler' => $handlerStack]);

        $response = $client->get('http://localhost:9123/test/api');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            ['test' => 'data'],
            json_decode($response->getBody(), true)
        );
    }

    public function test_real_mode_stores_responses()
    {
        putenv('USE_REAL_AI=true');
        $responsePath = storage_path('app/http_responses/post_test_api.json');

        $mock = new MockHandler([
            new Response(200, [], json_encode(['output' => 'test markdown']))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new MockableHttpClient(['handler' => $handlerStack]);

        $request = new Request(
            'POST',
            'http://localhost:9123/test/api',
            [],
            'test content'
        );

        $client->request($request->getMethod(), (string) $request->getUri());
        $this->assertFileExists($responsePath);

        // Clean up
        unlink($responsePath);
    }
}
