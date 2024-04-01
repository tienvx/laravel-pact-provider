<?php

namespace Tienvx\PactProvider\Tests\TestApplication\MessageDispatcher;

use Tienvx\PactProvider\Attribute\AsMessageDispatcher;
use Tienvx\PactProvider\MessageDispatcher\DispatcherInterface;
use Tienvx\PactProvider\Model\Message;

#[AsMessageDispatcher(description: 'no message')]
class NoMessageDispatcher implements DispatcherInterface
{
    public function dispatch(): ?Message
    {
        return null;
    }
}
