<?php

namespace Tienvx\PactProvider\Tests\TestApplication\MessageDispatcher;

use Tienvx\PactProvider\Attribute\AsMessageDispatcher;
use Tienvx\PactProvider\MessageDispatcher\DispatcherInterface;
use Tienvx\PactProvider\Model\Message;

#[AsMessageDispatcher(description: 'no message')]
class NoMessageDispatcher implements DispatcherInterface
{
    public function support(string $description): bool
    {
        return $description === 'no message';
    }

    public function dispatch(): ?Message
    {
        return null;
    }
}
