<?php

declare(strict_types=1);

namespace App\Lesson12\State;

use App\Lesson01\Command;
use App\Lesson07\HardStop\HardStopCommand;
use App\Lesson12\Commands\RunCommand;

class MoveToState implements State
{
    public function handle(Command $command): ?State
    {
        // todo: Command move to other queue
        $command->execute();

        if ($command instanceof HardStopCommand) {
            return null;
        }
        if ($command instanceof RunCommand) {
            return new BaseState();
        }

        return $this;
    }
}
