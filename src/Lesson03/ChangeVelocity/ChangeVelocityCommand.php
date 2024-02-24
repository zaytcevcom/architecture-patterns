<?php

declare(strict_types=1);

namespace App\Lesson03\ChangeVelocity;

use App\Lesson01\Command;
use App\Lesson01\Move\Movable;
use App\Lesson01\Move\Vector;
use App\Lesson01\Rotate\Rotatable;

readonly class ChangeVelocityCommand implements Command
{
    public function __construct(
        private Movable $movable,
        private Rotatable $rotatable,
    ) {}

    public function execute(): void
    {
        $velocity = $this->movable->getVelocity();

        if ($velocity->getX() === 0.0 && $velocity->getY() === 0.0) {
            return;
        }

        // todo

        $angle = $this->rotatable->getDirection()->getDirection() * M_PI / $this->rotatable->getDirection()->getDirectionsNumber();
        $newVelocityX = $velocity->getX() * cos($angle) - $velocity->getY() * sin($angle);
        $newVelocityY = $velocity->getX() * sin($angle) + $velocity->getY() * cos($angle);

        $this->movable->setPosition(new Vector($newVelocityX, $newVelocityY));
    }
}
