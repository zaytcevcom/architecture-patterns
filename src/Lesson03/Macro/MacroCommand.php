<?php

declare(strict_types=1);

namespace App\Lesson03\Macro;

use App\Lesson01\Command;

readonly class MacroCommand implements Command
{
    public function __construct(
        /** @var Command[] */
        private array $commands
    ) {}

    public function execute(): void
    {
        foreach ($this->commands as $command) {
            $command->execute();
        }
    }
}
