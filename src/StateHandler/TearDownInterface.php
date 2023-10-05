<?php

namespace Tienvx\PactProviderPackage\StateHandler;

interface TearDownInterface
{
    public function tearDown(array $params): void;
}
