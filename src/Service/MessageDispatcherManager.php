<?php

namespace Tienvx\PactProvider\Service;

use Tienvx\PactProvider\Exception\NoDispatcherForMessageException;
use Tienvx\PactProvider\Exception\LogicException;
use Tienvx\PactProvider\MessageDispatcher\DispatcherInterface;
use Tienvx\PactProvider\Model\Message;
use Traversable;

class MessageDispatcherManager implements MessageDispatcherManagerInterface
{
    public function __construct(private array | Traversable $dispatchers)
    {
    }

    public function dispatch(string $description): ?Message
    {
        foreach ($this->dispatchers as $dispatcher) {
            if (!$dispatcher instanceof DispatcherInterface) {
                throw new LogicException(sprintf(
                    'Dispatcher "%s" must implement "%s".',
                    get_debug_type($dispatcher),
                    DispatcherInterface::class
                ));
            }
            if ($dispatcher->support($description)) {
                return $dispatcher->dispatch();
            }
        }

        throw new NoDispatcherForMessageException(sprintf("No dispatcher for description '%s'.", $description));
    }
}
