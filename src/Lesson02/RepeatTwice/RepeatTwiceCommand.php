<?php

declare(strict_types=1);

namespace App\Lesson02\RepeatTwice;

use App\Lesson01\Command;

readonly class RepeatTwiceCommand implements Command
{
    public function __construct(
        private Command $command
    ) {}

    public function execute(): void
    {
        $this->command->execute();
    }
}
