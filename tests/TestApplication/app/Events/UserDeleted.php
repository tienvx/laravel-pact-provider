<?php

namespace Tienvx\PactProvider\Tests\TestApplication\Events;

class UserDeleted
{
    public function __construct(public readonly int $userId)
    {
    }
}
