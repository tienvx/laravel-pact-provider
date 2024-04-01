<?php

namespace Tienvx\PactProvider\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class AsStateHandler
{
    public function __construct(public string $state)
    {
    }
}
