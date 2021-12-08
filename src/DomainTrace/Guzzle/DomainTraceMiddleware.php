<?php

declare(strict_types=1);

namespace Forkrefactor\DddLogging\DomainTrace\Guzzle;

use Forkrefactor\DddLogging\DomainTrace\Tracker;

final class DomainTraceMiddleware
{
    public static function trace(Tracker $tracker): \Closure
    {
        return static fn (callable $handler) => static function ($request, array $options) use ($handler, $tracker) {
            $request = $request->withHeader(
                'x-correlation-id',
                $tracker->correlationId(),
            );

            $request = $request->withHeader(
                'x-reply-to',
                $tracker->replyTo(),
            );

            return $handler($request, $options);
        };
    }
}
