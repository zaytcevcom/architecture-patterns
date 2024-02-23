<?php

declare(strict_types=1);

namespace App\Lesson02\RepeatTwice;

use App\Lesson01\Command;

readonly class RepeatTwiceToQueueCommand implements Command
{
    public function __construct(
        private Command $command
    ) {}

    public function execute(): void
    {
        /** @psalm-var Command[] $queue */
        global $queue;

        $queue[] = new RepeatTwiceCommand($this->command);
    }
}
