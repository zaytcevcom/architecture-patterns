<?php

declare(strict_types=1);

namespace App\Lesson12\State;

use App\Lesson01\Command;

interface State
{
    public function handle(Command $command): ?self;
}
