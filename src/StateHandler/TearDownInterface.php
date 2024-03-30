<?php

namespace Tienvx\PactProvider\StateHandler;

interface TearDownInterface
{
    public function tearDown(array $params): void;
}
