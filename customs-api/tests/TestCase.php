<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function generateFakeMarkerResponse(string $markdownContent): array
    {
        return [
            'format' => 'markdown',
            'output' => $markdownContent,
            'images' => [
                '_page_0_Picture_0.jpeg' => 'base64encoded',
                '_page_1_Picture_0.jpeg' => 'base64encoded',
            ],
            'metadata' => [
                'table_of_contents' => [],
                'page_stats' => [
                    [
                        'page_id' => 0,
                        'text_extraction_method' => 'pdftext',
                        'block_counts' => [],
                        'block_metadata' => [
                            'llm_request_count' => 0,
                            'llm_error_count' => 0,
                            'llm_tokens_used' => 0
                        ]
                    ]
                ],
                'debug_data_path' => 'debug_data/test-file'
            ],
            'success' => true
        ];
    }
}
