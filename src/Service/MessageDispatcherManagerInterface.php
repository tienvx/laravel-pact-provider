<?php

namespace Tienvx\PactProvider\Service;

use Tienvx\PactProvider\Model\Message;

interface MessageDispatcherManagerInterface
{
    public function dispatch(string $description): ?Message;
}
