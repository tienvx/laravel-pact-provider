<?php

namespace Tienvx\PactProviderPackage\Service;

use Tienvx\PactProviderPackage\Enum\Action;
use Tienvx\PactProviderPackage\Exception\LogicException;
use Tienvx\PactProviderPackage\Model\StateValues;
use Tienvx\PactProviderPackage\StateHandler\HandlerInterface;
use Tienvx\PactProviderPackage\StateHandler\SetUpInterface;
use Tienvx\PactProviderPackage\StateHandler\TearDownInterface;

class StateHandlerManager implements StateHandlerManagerInterface
{
    public function handle(string $state, string $action, array $params): ?StateValues
    {
        foreach (app()->tagged('pact_provider.state_handler') as $handler) {
            if ($handler instanceof HandlerInterface && $handler->support($state)) {
                switch ($action) {
                    case Action::SETUP:
                        if (!$handler instanceof SetUpInterface) {
                            throw new LogicException(sprintf('Handler "%s" must implement "%s".', get_debug_type($handler), SetUpInterface::class));
                        }
        
                        return $handler->setUp($params);
        
                    case Action::TEARDOWN:
                        if (!$handler instanceof TearDownInterface) {
                            throw new LogicException(sprintf('Handler "%s" must implement "%s".', get_debug_type($handler), TearDownInterface::class));
                        }
                        $handler->tearDown($params);
                        break;
        
                    default:
                        break;
                }
            }
        }

        return null;
    }
}
