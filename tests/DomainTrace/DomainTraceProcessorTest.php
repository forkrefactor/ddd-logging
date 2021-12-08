<?php

declare(strict_types=1);

namespace Forkrefactor\DddLogging\Tests\DomainTrace;

use Forkrefactor\DddLogging\DomainTrace\DomainTraceProcessor;
use Forkrefactor\DddLogging\DomainTrace\Tracker;
use PHPUnit\Framework\TestCase;

final class DomainTraceProcessorTest extends TestCase
{
    public function testShouldAddedReplyToAndCorrelationIdToRecord()
    {
        $correlationId = "correlation_id_value";
        $replyTo = "reply_to_value";
        $record = [
            'context' => [],
        ];

        $trackerMock = $this->createMock(Tracker::class);
        $trackerMock
            ->expects($this->once())
            ->method('correlationId')
            ->willReturn($correlationId);

        $trackerMock
            ->expects($this->once())
            ->method('replyTo')
            ->willReturn($replyTo);

        $record = (new DomainTraceProcessor($trackerMock))($record);

        $this->assertEquals($correlationId, $record['extra']['correlation_id']);
        $this->assertEquals($replyTo, $record['extra']['reply_to']);
    }
}