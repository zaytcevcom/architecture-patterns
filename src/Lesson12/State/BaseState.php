<?php

declare(strict_types=1);

namespace App\Lesson12\State;

use App\Lesson01\Command;
use App\Lesson07\HardStop\HardStopCommand;
use App\Lesson12\Commands\MoveToCommand;

class BaseState implements State
{
    public function handle(Command $command): ?State
    {
        $command->execute();

        if ($command instanceof HardStopCommand) {
            return null;
        }
        if ($command instanceof MoveToCommand) {
            return new MoveToState();
        }

        return $this;
    }
}
