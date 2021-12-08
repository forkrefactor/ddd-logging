<?php

declare(strict_types=1);

namespace Forkrefactor\DddLogging\MessageLogger;

interface Action
{
    public function success(): string;
    public function error(): string;
}
