<?php

namespace Tienvx\PactProvider\Tests\Feature\Controllers;

use PHPUnit\Framework\Attributes\TestWith;
use Tienvx\PactProvider\Enum\Action;
use Tienvx\PactProvider\Tests\Feature\PactProviderTestCase;

class StateChangeControllerTest extends PactProviderTestCase
{
    public function testWrongUrl(): void
    {
        $crawler = $this->post('/pact-change-state-not-found');

        $this->assertStringContainsString('Not Found', $crawler->content());
        $crawler->assertStatus(404);
    }

    public function testWrongMethod(): void
    {
        $crawler = $this->get('/pact-change-state');

        $this->assertStringContainsString(
            'The GET method is not supported for route pact-change-state. Supported methods: POST',
            $crawler->content()
        );
        $crawler->assertStatus(405);
    }

    public function testRequestBodyIsEmpty(): void
    {
        $crawler = $this->post('/pact-change-state');

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'action' => ['The action field is required.'],
                'state' => ['The state field is required.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    public function testMissingProviderStateNameInBody(): void
    {
        $crawler = $this->post('/pact-change-state', [
            'params' => [
                'key' => 'value',
            ],
            'action' => Action::SETUP->value,
        ]);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'state' => ['The state field is required.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    public function testInvalidProviderStateNameInBody(): void
    {
        $crawler = $this->post('/pact-change-state', [
            'params' => [
                'key' => 'value',
            ],
            'action' => Action::SETUP->value,
            'state' => 123,
        ]);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'state' => ['The state field must be a string.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    public function testInvalidProviderStateParamsInBody(): void
    {
        $crawler = $this->post('/pact-change-state', [
            'state' => 'has values',
            'params' => 123,
            'action' => Action::SETUP->value,
        ]);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'params' => ['The params field must be an array.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    public function testMissingProviderStateActionInBody(): void
    {
        $crawler = $this->post('/pact-change-state', [
            'state' => 'has values',
            'params' => [
                'key' => 'value',
            ],
        ]);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'action' => ['The action field is required.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    public function testInvalidProviderStateActionInBody(): void
    {
        $crawler = $this->post('/pact-change-state', [
            'state' => 'has values',
            'params' => [
                'key' => 'value',
            ],
            'action' => 'clean',
        ]);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'action' => ['The selected action is invalid.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    #[TestWith([Action::SETUP])]
    #[TestWith([Action::TEARDOWN])]
    public function testNoValuesStateInBody(Action $action): void
    {
        $crawler = $this->post('/pact-change-state', [
            'state' => 'no values',
            'params' => [
                'key' => 'value',
            ],
            'action' => $action->value,
        ]);

        $crawler->assertNoContent();
    }

    public function testHasValuesStateInBody(): void
    {
        $crawler = $this->post('/pact-change-state', [
            'state' => 'has values',
            'params' => [
                'key' => 'value',
            ],
            'action' => Action::SETUP->value,
        ]);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'id' => 123,
        ]), $crawler->content());
        $crawler->assertStatus(200);
        $crawler->assertHeader('Content-Type', 'application/json');
    }

    public function testMissingProviderStateNameInQuery(): void
    {
        config(['pact_provider.state_change.body' => false]);
        $crawler = $this->post('/pact-change-state?' . http_build_query([
            'key' => 'value',
            'action' => Action::SETUP->value,
        ]));

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'state' => ['The state field is required.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    public function testInvalidProviderStateNameInQuery(): void
    {
        config(['pact_provider.state_change.body' => false]);
        $crawler = $this->post('/pact-change-state?' . http_build_query([
            'key' => 'value',
            'action' => Action::SETUP->value,
            'state[]' => 'has values',
        ]));

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'state' => ['The state field must be a string.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    public function testMissingProviderStateActionInQuery(): void
    {
        config(['pact_provider.state_change.body' => false]);
        $crawler = $this->post('/pact-change-state?' . http_build_query([
            'state' => 'has values',
            'key' => 'value',
        ]));

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'action' => ['The action field is required.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    public function testInvalidProviderStateActionInQuery(): void
    {
        config(['pact_provider.state_change.body' => false]);
        $crawler = $this->post('/pact-change-state?' . http_build_query([
            'state' => 'has values',
            'key' => 'value',
            'action' => 'clean',
        ]));

        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'failed',
            'message' => [
                'action' => ['The selected action is invalid.'],
            ],
        ]), $crawler->content());
        $crawler->assertStatus(400);
    }

    #[TestWith([Action::SETUP])]
    #[TestWith([Action::TEARDOWN])]
    public function testNoValuesStateInQuery(Action $action): void
    {
        config(['pact_provider.state_change.body' => false]);
        $crawler = $this->post('/pact-change-state?' . http_build_query([
            'state' => 'no values',
            'key' => 'value',
            'action' => $action->value,
        ]));

        $crawler->assertNoContent();
    }

    public function testHasValuesStateInQuery(): void
    {
        config(['pact_provider.state_change.body' => false]);
        $crawler = $this->post('/pact-change-state?' . http_build_query([
            'state' => 'has values',
            'key' => 'value',
            'action' => Action::SETUP->value,
        ]));

        $this->assertJsonStringEqualsJsonString(json_encode([
            'id' => 123,
        ]), $crawler->content());
        $crawler->assertStatus(200);
        $crawler->assertHeader('Content-Type', 'application/json');
    }
}
