<?php

namespace Tienvx\PactProvider\Tests\Feature\Controllers;

use PHPUnit\Framework\Attributes\TestWith;
use Tienvx\PactProvider\Tests\Feature\PactProviderTestCase;

class MessagesControllerTest extends PactProviderTestCase
{
    public function testWrongUrl(): void
    {
        $crawler = $this->post('/pact-messages-not-found');

        $this->assertStringContainsString('Not Found', $crawler->content());
        $crawler->assertStatus(404);
    }

    public function testWrongMethod(): void
    {
        $crawler = $this->get('/pact-messages');

        $this->assertStringContainsString(
            'The GET method is not supported for route pact-messages. Supported methods: POST',
            $crawler->content()
        );
        $crawler->assertStatus(405);
    }

    public function testRequestBodyIsEmpty(): void
    {
        $crawler = $this->post('/pact-messages');

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'description' => ['The description field is required.'],
                'providerStates' => ['The provider states field is required.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    #[TestWith([null])]
    #[TestWith([[]])]
    public function testMissingOrEmptyProviderStates(mixed $value): void
    {
        $crawler = $this->post('/pact-messages', [
            'description' => 'has message',
            'providerStates' => $value,
        ]);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'providerStates' => ['The provider states field is required.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    public function testInvalidProviderStates(): void
    {
        $crawler = $this->post('/pact-messages', [
            'description' => 'has message',
            'providerStates' => 123,
        ]);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'providerStates' => ['The provider states field must be an array.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    public function testMissingProviderStateName(): void
    {
        $crawler = $this->post('/pact-messages', [
            'description' => 'has message',
            'providerStates' => [
                [
                    'params' => [
                        'key' => 'value',
                    ],
                ],
            ],
        ]);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'providerStates.0.name' => ['The providerStates.0.name field is required.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    public function testInvalidProviderStateName(): void
    {
        $crawler = $this->post('/pact-messages', [
            'description' => 'has message',
            'providerStates' => [
                [
                    'name' => 123,
                    'params' => [
                        'key' => 'value',
                    ],
                ],
            ],
        ]);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'providerStates.0.name' => ['The providerStates.0.name field must be a string.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    public function testInvalidProviderStateParams(): void
    {
        $crawler = $this->post('/pact-messages', [
            'description' => 'has message',
            'providerStates' => [
                [
                    'name' => 'has values',
                    'params' => 123,
                ],
            ],
        ]);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'providerStates.0.params' => ['The providerStates.0.params field must be an array.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    public function testMissingDescription(): void
    {
        $crawler = $this->post('/pact-messages', [
            'providerStates' => [
                [
                    'name' => 'has values',
                    'params' => [
                        'key' => 'value',
                    ],
                ],
            ],
        ]);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'description' => ['The description field is required.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    public function testNoMessage(): void
    {
        $crawler = $this->post('/pact-messages', [
            'description' => 'no message',
            'providerStates' => [
                [
                    'name' => 'has values',
                    'params' => [
                        'key' => 'value',
                    ],
                ],
            ],
        ]);

        $crawler->assertContent('');
        $crawler->assertStatus(200);
    }

    public function testHasMessage(): void
    {
        $crawler = $this->post('/pact-messages', [
            'description' => 'has message',
            'providerStates' => [
                [
                    'name' => 'has values',
                    'params' => [
                        'key' => 'value',
                    ],
                ],
            ],
        ]);

        $crawler->assertContent('message content');
        $crawler->assertStatus(200);
        $crawler->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $crawler->assertHeader('Pact-Message-Metadata', 'eyJrZXkiOiJ2YWx1ZSIsImNvbnRlbnRUeXBlIjoidGV4dFwvcGxhaW4ifQ==');
    }
}
