<?php

namespace Tienvx\PactProvider\Service;

use Tienvx\PactProvider\Enum\Action;
use Tienvx\PactProvider\Model\StateValues;

interface StateHandlerManagerInterface
{
    public function handle(string $state, Action $action, array $params): ?StateValues;
}
