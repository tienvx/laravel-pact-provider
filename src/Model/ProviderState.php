<?php

namespace Tienvx\PactProvider\Model;

class ProviderState
{
    public function __construct(
        public readonly string $state,
        public readonly array $params
    ) {
    }
}
