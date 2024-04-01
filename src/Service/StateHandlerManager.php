<?php

namespace Tienvx\PactProvider\Service;

use ReflectionAttribute;
use ReflectionClass;
use Tienvx\PactProvider\Attribute\AsStateHandler;
use Tienvx\PactProvider\Exception\NoHandlerForStateException;
use Tienvx\PactProvider\Enum\Action;
use Tienvx\PactProvider\Exception\LogicException;
use Tienvx\PactProvider\Model\StateValues;
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
            if (!$this->support($handler, $state)) {
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

    private function support(SetUpInterface|TearDownInterface $handler, string $state): bool
    {
        $reflection = new ReflectionClass($handler::class);
        $attributes = $reflection->getAttributes(AsStateHandler::class);

        return count(array_filter(
            $attributes,
            fn (ReflectionAttribute $attribute) => $attribute->newInstance()->state === $state
        )) > 0;
    }
}
