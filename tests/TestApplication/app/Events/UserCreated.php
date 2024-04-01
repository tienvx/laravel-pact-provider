<?php

namespace Tienvx\PactProvider\Tests\TestApplication\Events;

class UserCreated
{
    public function __construct(public readonly int $userId)
    {
    }
}
