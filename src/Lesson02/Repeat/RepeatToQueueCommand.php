<?php

declare(strict_types=1);

namespace App\Lesson02\Repeat;

use App\Lesson01\Command;

readonly class RepeatToQueueCommand implements Command
{
    public function __construct(
        private Command $command
    ) {}

    public function execute(): void
    {
        /** @psalm-var Command[] $queue */
        global $queue;

        $queue[] = new RepeatCommand($this->command);
    }
}
