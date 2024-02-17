<?php

declare(strict_types=1);

namespace App\Lesson01;

interface Command
{
    public function execute(): void;
}
