<?php

namespace Tienvx\PactProvider\Tests\Feature\Listeners;

use Tienvx\PactProvider\Listeners\EventCollectorInterface;
use Tienvx\PactProvider\Tests\TestApplication\Events\UserCreated;
use Tienvx\PactProvider\Tests\TestApplication\Events\UserDeleted;
use Tienvx\PactProvider\Tests\TestApplication\Events\UserUpdated;
use Tienvx\PactProvider\Tests\Feature\PactProviderTestCase;

class EventCollectorTest extends PactProviderTestCase
{
    public function testCreateUser(): void
    {
        $crawler = $this->post('/create');
        $crawler->assertStatus(200);

        $collector = app(EventCollectorInterface::class);
        $this->assertGreaterThan(1, count($all = $collector->getAll()));

        $this->assertInstanceOf(UserCreated::class, $created = $collector->getSingle(UserCreated::class));
        $this->assertTrue(in_array($created, $all));
        $this->assertSame(123, $created->userId);
    }

    public function testUpdateUser(): void
    {
        $crawler = $this->put('/update/123');
        $crawler->assertStatus(200);

        $collector = app(EventCollectorInterface::class);
        $this->assertGreaterThan(1, count($all = $collector->getAll()));

        $this->assertInstanceOf(UserUpdated::class, $updated = $collector->getSingle(UserUpdated::class));
        $this->assertTrue(in_array($updated, $all));
        $this->assertSame(123, $updated->userId);
    }

    public function testDeleteUser(): void
    {
        $crawler = $this->delete('/delete/123');
        $crawler->assertStatus(200);

        $collector = app(EventCollectorInterface::class);
        $this->assertGreaterThan(1, count($all = $collector->getAll()));

        $this->assertInstanceOf(UserDeleted::class, $deleted = $collector->getSingle(UserDeleted::class));
        $this->assertTrue(in_array($deleted, $all));
        $this->assertSame(123, $deleted->userId);
    }
}
