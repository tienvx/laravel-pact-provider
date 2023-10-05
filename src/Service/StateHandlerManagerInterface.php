<?php

namespace Tienvx\PactProviderPackage\Service;

use Tienvx\PactProviderPackage\Model\StateValues;

interface StateHandlerManagerInterface
{
    public function handle(string $state, string $action, array $params): ?StateValues;
}
