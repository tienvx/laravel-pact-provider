<?php

namespace Tienvx\PactProvider\Enum;

enum Action: string
{
    case SETUP = 'setup';
    case TEARDOWN = 'teardown';
}
