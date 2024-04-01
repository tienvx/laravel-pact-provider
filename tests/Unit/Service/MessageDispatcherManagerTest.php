<?php

namespace Tienvx\PactProvider\Tests\Unit\Service;

use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Tienvx\PactProvider\Exception\LogicException;
use Tienvx\PactProvider\Exception\NoDispatcherForMessageException;
use Tienvx\PactProvider\MessageDispatcher\DispatcherInterface;
use Tienvx\PactProvider\Model\Message;
use Tienvx\PactProvider\Service\MessageDispatcherManager;
use Tienvx\PactProvider\Service\MessageDispatcherManagerInterface;

class MessageDispatcherManagerTest extends TestCase
{
    private MessageDispatcherManagerInterface $messageDispatcherManager;
    private array $params = ['key' => 'value'];

    public function testNoDispatcher(): void
    {
        $description = 'no dispatcher';
        $this->messageDispatcherManager = new MessageDispatcherManager([]);
        $this->expectException(NoDispatcherForMessageException::class);
        $this->expectExceptionMessage(sprintf("No dispatcher for description '%s'.", $description));
        $this->messageDispatcherManager->dispatch($description);
    }

    public function testSetupInvalidDispatcher(): void
    {
        $description = 'invalid dispatcher';
        $handler = function () {
        };
        $this->messageDispatcherManager = new MessageDispatcherManager([
            $description => $handler,
        ]);
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(sprintf('Dispatcher "Closure" must implement "%s".', DispatcherInterface::class));
        $this->messageDispatcherManager->dispatch($description);
    }

    #[TestWith([null])]
    #[TestWith([new Message('contents', 'type/subtype', 'extra info')])]
    public function testDispatch(?Message $message): void
    {
        $description = 'message event';
        $dispatcher = $this->createMock(DispatcherInterface::class);
        $dispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->willReturn($message);
        $this->messageDispatcherManager = new MessageDispatcherManager([
            $description => $dispatcher,
        ]);
        $this->assertSame($message, $this->messageDispatcherManager->dispatch($description));
    }
}
