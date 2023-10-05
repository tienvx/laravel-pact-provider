<?php

namespace Tienvx\PactProviderPackage\Service;

use Tienvx\PactProviderPackage\Model\Message;

interface MessageDispatcherManagerInterface
{
    public function dispatch(string $description): ?Message;
}
