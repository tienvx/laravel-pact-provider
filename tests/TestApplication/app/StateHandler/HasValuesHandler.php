<?php

namespace Tienvx\PactProvider\Tests\TestApplication\StateHandler;

use Tienvx\PactProvider\Attribute\AsStateHandler;
use Tienvx\PactProvider\Model\StateValues;
use Tienvx\PactProvider\StateHandler\SetUpInterface;
use Tienvx\PactProvider\StateHandler\TearDownInterface;

#[AsStateHandler(state: 'has values')]
class HasValuesHandler implements SetUpInterface, TearDownInterface
{
    public function setUp(array $params): ?StateValues
    {
        return new StateValues([
            'id' => 123,
        ]);
    }

    public function tearDown(array $params): void
    {
    }
}
