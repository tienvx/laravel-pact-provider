<?php

namespace Tienvx\PactProviderPackage\Enum;

class Action
{
    public const SETUP = 'setup';
    public const TEARDOWN = 'teardown';

    public static function all(): array
    {
        return [self::SETUP, self::TEARDOWN];
    }
}
