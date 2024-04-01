<?php

namespace Tienvx\PactProvider\Tests\Unit\Listeners;

use PHPUnit\Framework\TestCase;
use Tienvx\PactProvider\Listeners\EventCollector;
use Tienvx\PactProvider\Listeners\EventCollectorInterface;
use Tienvx\PactProvider\Tests\TestApplication\Events\UserCreated;
use Tienvx\PactProvider\Tests\TestApplication\Events\UserDeleted;
use Tienvx\PactProvider\Tests\TestApplication\Events\UserUpdated;

class EventCollectorTest extends TestCase
{
    private EventCollectorInterface $collector;
    private UserCreated $create;
    private UserUpdated $update;
    private UserDeleted $delete;

    protected function setUp(): void
    {
        $this->collector = new EventCollector();
        $this->collector->collect(UserCreated::class, [$this->create = new UserCreated(123)]);
        $this->collector->collect(UserUpdated::class, [$this->update = new UserUpdated(123)]);
        $this->collector->collect(UserDeleted::class, [$this->delete = new UserDeleted(123)]);
    }

    public function testGetAll(): void
    {
        $this->assertSame([$this->create, $this->update, $this->delete], $this->collector->getAll());
    }

    public function testSingleFound(): void
    {
        $this->assertSame($this->create, $this->collector->getSingle(UserCreated::class));
    }

    public function testSingleNotFound(): void
    {
        $this->assertNull($this->collector->getSingle(\stdClass::class));
    }
}
