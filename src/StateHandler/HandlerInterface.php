<?php

namespace Tienvx\PactProvider\StateHandler;

interface HandlerInterface
{
    public function support(string $state): bool;
}
