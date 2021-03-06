<?php

declare(strict_types=1);

namespace Forkrefactor\DddLogging\ExecutionTime;

use Monolog\Processor\ProcessorInterface;
use Forkrefactor\Ddd\Util\Message\Message;
use Symfony\Component\Stopwatch\Stopwatch;

final class ExecutionTimeProcessor implements ProcessorInterface
{
    private Stopwatch $stopwatch;

    public function __construct(Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
    }

    public function __invoke(array $record): array
    {
        if (false === \array_key_exists('message', $record['context'])) {
            return $record;
        }

        $message = $record['context']['message'];

        if (false === $message instanceof Message) {
            return $record;
        }

        $record['extra']['execution_time'] = $this->getExecutionTime($message);

        return $record;
    }

    private function getExecutionTime(Message $message): float
    {
        $duration = 0;

        try {
            $event = $this->stopwatch->getEvent(
                $message->messageId()->value(),
                );

            $duration = $event->getDuration();
        } catch (\LogicException $exception) {
        }

        return \round(
            $duration / 1000,
            6,
        );
    }
}
