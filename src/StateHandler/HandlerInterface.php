<?php

namespace Tienvx\PactProviderPackage\StateHandler;

interface HandlerInterface
{
    public function support(string $state): bool;
}
