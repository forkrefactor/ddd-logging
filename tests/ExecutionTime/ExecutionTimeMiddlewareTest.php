<?php

declare(strict_types=1);

namespace Forkrefactor\DddLogging\Tests\ExecutionTime;

use Forkrefactor\Ddd\Domain\Model\ValueObject\Uuid;
use Forkrefactor\Ddd\Util\Message\Message;
use Forkrefactor\DddLogging\ExecutionTime\ExecutionTimeMiddleware;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Stopwatch\Stopwatch;

final class ExecutionTimeMiddlewareTest extends TestCase
{
    public function testShouldMeasuredExecutionTime()
    {
        $toStringUuid = 'e62d7245-57b3-4842-9c7f-f7a89a439450';
        $mockMessageId = $this->createMock(Uuid::class);
        $mockMessage = $this->createMock(Message::class);
        $mockEnvelope = $this->createMock(Envelope::class);
        $middlewareMock = $this->createMock(MiddlewareInterface::class);
        $mockStack = $this->createMock(StackInterface::class);
        $mockStopwatch = $this->createMock(Stopwatch::class);

        $mockMessageId
            ->expects($this->once())
            ->method('value')
            ->willReturn($toStringUuid);

        $mockMessage
            ->expects($this->once())
            ->method('messageId')
            ->willReturn($mockMessageId);

        $mockEnvelope
            ->expects($this->once())
            ->method('getMessage')
            ->willReturn($mockMessage);

        $middlewareMock
            ->expects($this->once())
            ->method('handle')
            ->with($mockEnvelope, $mockStack);

        $mockStack
            ->expects($this->once())
            ->method('next')
            ->willReturn($middlewareMock);

        $mockStopwatch
            ->expects($this->once())
            ->method('isStarted')
            ->willReturn(true);
        $mockStopwatch
            ->expects($this->once())
            ->method('reset');
        $mockStopwatch
            ->expects($this->once())
            ->method('start');
        $mockStopwatch
            ->expects($this->once())
            ->method('stop');

        $result = (new ExecutionTimeMiddleware($mockStopwatch))->handle($mockEnvelope, $mockStack);

        $this->assertInstanceOf(Envelope::class, $result);

    }
}
