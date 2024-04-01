<?php

namespace Tienvx\PactProvider\Tests\TestApplication\MessageDispatcher;

use Tienvx\PactProvider\Attribute\AsMessageDispatcher;
use Tienvx\PactProvider\MessageDispatcher\DispatcherInterface;
use Tienvx\PactProvider\Model\Message;

#[AsMessageDispatcher(description: 'has message')]
class HasMessageDispatcher implements DispatcherInterface
{
    public function dispatch(): ?Message
    {
        return new Message(
            'message content',
            'text/plain',
            json_encode(['key' => 'value', 'contentType' => 'text/plain'])
        );
    }
}
