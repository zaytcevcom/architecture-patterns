<?php

declare(strict_types=1);

namespace App\Lesson12\State;

use App\Lesson01\Command;

class Context
{
    private ?State $state = null;

    public function setState(?State $state): void
    {
        $this->state = $state;
    }

    public function request(Command $command): ?State
    {
        return $this->state?->handle($command);
    }
}
