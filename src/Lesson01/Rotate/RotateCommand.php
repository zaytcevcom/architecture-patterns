<?php

declare(strict_types=1);

namespace App\Lesson01\Rotate;

use App\Lesson01\Command;

readonly class RotateCommand implements Command
{
    public function __construct(
        private Rotatable $rotatable
    ) {}

    public function execute(): void
    {
        $this->rotatable->setDirection(
            $this->rotatable->getDirection()->next(
                $this->rotatable->getAngularVelocity()
            )
        );
    }
}
