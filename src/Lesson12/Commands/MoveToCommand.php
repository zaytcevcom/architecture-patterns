<?php

declare(strict_types=1);

namespace App\Lesson12\Commands;

use App\Lesson01\Command;

class MoveToCommand implements Command
{
    public function execute(): void
    {
        (new LogCommand('State MoveToCommand enabled'))->execute();
    }
}
