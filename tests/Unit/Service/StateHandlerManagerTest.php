<?php

namespace Tienvx\PactProvider\Tests\Unit\Service;

use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Tienvx\PactProvider\Enum\Action;
use Tienvx\PactProvider\Exception\LogicException;
use Tienvx\PactProvider\Exception\NoHandlerForStateException;
use Tienvx\PactProvider\Model\StateValues;
use Tienvx\PactProvider\Service\StateHandlerManager;
use Tienvx\PactProvider\Service\StateHandlerManagerInterface;
use Tienvx\PactProvider\StateHandler\SetUpInterface;
use Tienvx\PactProvider\StateHandler\TearDownInterface;

class StateHandlerManagerTest extends TestCase
{
    private StateHandlerManagerInterface $stateHandlerManager;
    private array $params = ['key' => 'value'];

    #[TestWith([Action::SETUP])]
    #[TestWith([Action::TEARDOWN])]
    public function testNoHandler(Action $action): void
    {
        $state = 'no handler';
        $this->stateHandlerManager = new StateHandlerManager([]);
        $this->expectException(NoHandlerForStateException::class);
        $this->expectExceptionMessage(sprintf("No handler for state '%s'.", $state));
        $this->stateHandlerManager->handle($state, $action, $this->params);
    }

    public function testSetupInvalidHandler(): void
    {
        $state = 'setup invalid handler';
        $action = Action::SETUP;
        $this->stateHandlerManager = new StateHandlerManager([
            $state => function () {
            },
        ]);
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(sprintf('Handler "Closure" must implement "%s".', SetUpInterface::class));
        $this->stateHandlerManager->handle($state, $action, $this->params);
    }

    public function testTeardownInvalidHandler(): void
    {
        $state = 'teardown invalid handler';
        $action = Action::TEARDOWN;
        $this->stateHandlerManager = new StateHandlerManager([
            $state => function () {
            },
        ]);
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(sprintf('Handler "Closure" must implement "%s".', TearDownInterface::class));
        $this->stateHandlerManager->handle($state, $action, $this->params);
    }

    #[TestWith([null])]
    #[TestWith([new StateValues(['key' => 'value'])])]
    public function testSetup(?StateValues $stateValues): void
    {
        $state = 'setup state';
        $action = Action::SETUP;
        $handler = $this->createMock(SetUpInterface::class);
        $handler
            ->expects($this->once())
            ->method('setUp')
            ->with($this->params)
            ->willReturn($stateValues);
        $this->stateHandlerManager = new StateHandlerManager([
            $state => $handler,
        ]);
        $this->assertSame($stateValues, $this->stateHandlerManager->handle($state, $action, $this->params));
    }

    public function testTeardown(): void
    {
        $state = 'teardown state';
        $action = Action::TEARDOWN;
        $handler = $this->createMock(TearDownInterface::class);
        $handler
            ->expects($this->once())
            ->method('tearDown')
            ->with($this->params);
        $this->stateHandlerManager = new StateHandlerManager([
            $state => $handler,
        ]);
        $this->assertNull($this->stateHandlerManager->handle($state, $action, $this->params));
    }
}
