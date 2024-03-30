<?php

namespace Tienvx\PactProvider\MessageDispatcher;

use Tienvx\PactProvider\Model\Message;

interface DispatcherInterface
{
    public function support(string $description): bool;

    public function dispatch(): ?Message;
}
