<?php

namespace Tienvx\PactProviderPackage\Service;

use Tienvx\PactProviderPackage\MessageDispatcher\DispatcherInterface;
use Tienvx\PactProviderPackage\Model\Message;

class MessageDispatcherManager implements MessageDispatcherManagerInterface
{
    public function dispatch(string $description): ?Message
    {
        foreach (app()->tagged('pact_provider.message_dispatcher') as $dispatcher) {
            if ($dispatcher instanceof DispatcherInterface && $dispatcher->support($description)) {
                return $dispatcher->dispatch();
            }
        }

        return null;
    }
}
