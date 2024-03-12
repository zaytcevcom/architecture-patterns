<?php

declare(strict_types=1);

namespace App\Lesson07\SafeQueue;

use App\Lesson01\Command;

interface Receiver
{
    public function push(Command $command): void;

    public function take(): Command;

    public function length(): int;

    public function isEmpty(): bool;
}
