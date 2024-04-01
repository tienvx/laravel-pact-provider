<?php

namespace Tienvx\PactProvider\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class AsMessageDispatcher
{
    public function __construct(public string $description)
    {
    }
}
