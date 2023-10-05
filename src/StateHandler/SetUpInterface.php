<?php

namespace Tienvx\PactProviderPackage\StateHandler;

use Tienvx\PactProviderPackage\Model\StateValues;

interface SetUpInterface
{
    public function setUp(array $params): ?StateValues;
}
