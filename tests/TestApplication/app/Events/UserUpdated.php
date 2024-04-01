<?php

namespace Tienvx\PactProvider\Tests\TestApplication\Events;

class UserUpdated
{
    public function __construct(public readonly int $userId)
    {
    }
}
