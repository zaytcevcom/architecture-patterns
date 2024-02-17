<?php

declare(strict_types=1);

namespace App\Lesson01\Move;

use App\Lesson01\Command;

readonly class MoveCommand implements Command
{
    public function __construct(
        private Movable $movable
    ) {}

    public function execute(): void
    {
        $this->movable->setPosition(
            Vector::plus(
                $this->movable->getPosition(),
                $this->movable->getVelocity()
            )
        );
    }
}
