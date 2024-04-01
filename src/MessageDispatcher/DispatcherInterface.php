<?php

namespace Tienvx\PactProvider\MessageDispatcher;

use Tienvx\PactProvider\Model\Message;

interface DispatcherInterface
{
    public function dispatch(): ?Message;
}
