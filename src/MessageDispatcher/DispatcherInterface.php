<?php

namespace Tienvx\PactProviderPackage\MessageDispatcher;

use Tienvx\PactProviderPackage\Model\Message;

interface DispatcherInterface
{
    public function support(string $description): bool;

    public function dispatch(): ?Message;
}
