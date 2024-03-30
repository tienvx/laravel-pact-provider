<?php

namespace Tienvx\PactProvider\StateHandler;

use Tienvx\PactProvider\Model\StateValues;

interface SetUpInterface
{
    public function setUp(array $params): ?StateValues;
}
