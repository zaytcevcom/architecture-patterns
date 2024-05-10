<?php

declare(strict_types=1);

namespace App\Lesson11\CheckCollision;

use App\Lesson01\Command;
use App\Lesson01\Move\Movable;

readonly class CheckCollisionCommand implements Command
{
    public function __construct(
        private Movable $firstObject,
        private Movable $secondObject,
    ) {}

    public function execute(): void
    {
        // TODO: Implement execute() method.
    }
}
