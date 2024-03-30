<?php

namespace Tienvx\PactProvider\Service;

use Tienvx\PactProvider\Exception\NoHandlerForStateException;
use Tienvx\PactProvider\Enum\Action;
use Tienvx\PactProvider\Exception\LogicException;
use Tienvx\PactProvider\Model\StateValues;
use Tienvx\PactProvider\StateHandler\HandlerInterface;
use Tienvx\PactProvider\StateHandler\SetUpInterface;
use Tienvx\PactProvider\StateHandler\TearDownInterface;
use Traversable;

class StateHandlerManager implements StateHandlerManagerInterface
{
    public function __construct(private array | Traversable $handlers)
    {
    }

    public function handle(string $state, Action $action, array $params): ?StateValues
    {
        foreach ($this->handlers as $handler) {
            if (!$handler instanceof HandlerInterface) {
                throw new LogicException(sprintf(
                    'Handler "%s" must implement "%s".',
                    get_debug_type($handler),
                    HandlerInterface::class
                ));
            }
            if (!$handler->support($state)) {
                continue;
            }
            switch ($action) {
                case Action::SETUP:
                    if (!$handler instanceof SetUpInterface) {
                        throw new LogicException(sprintf(
                            'Handler "%s" must implement "%s".',
                            get_debug_type($handler),
                            SetUpInterface::class
                        ));
                    }

                    return $handler->setUp($params);

                case Action::TEARDOWN:
                    if (!$handler instanceof TearDownInterface) {
                        throw new LogicException(sprintf(
                            'Handler "%s" must implement "%s".',
                            get_debug_type($handler),
                            TearDownInterface::class
                        ));
                    }
                    $handler->tearDown($params);

                    return null;

                default:
                    break;
            }
        }

        throw new NoHandlerForStateException(sprintf("No handler for state '%s'.", $state));
    }
}
