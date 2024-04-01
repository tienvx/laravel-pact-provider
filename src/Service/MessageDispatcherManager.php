<?php

namespace Tienvx\PactProvider\Service;

use Tienvx\PactProvider\Exception\NoDispatcherForMessageException;
use Tienvx\PactProvider\Exception\LogicException;
use Tienvx\PactProvider\MessageDispatcher\DispatcherInterface;
use Tienvx\PactProvider\Model\Message;

class MessageDispatcherManager implements MessageDispatcherManagerInterface
{
    public function __construct(private array $dispatchers)
    {
    }

    public function dispatch(string $description): ?Message
    {
        if (!isset($this->dispatchers[$description])) {
            throw new NoDispatcherForMessageException(sprintf("No dispatcher for description '%s'.", $description));
        }
        $dispatcher = $this->dispatchers[$description];
        if (!$dispatcher instanceof DispatcherInterface) {
            throw new LogicException(sprintf(
                'Dispatcher "%s" must implement "%s".',
                get_debug_type($dispatcher),
                DispatcherInterface::class
            ));
        }

        return $dispatcher->dispatch();
    }
}
