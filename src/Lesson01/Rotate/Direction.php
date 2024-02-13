<?php

declare(strict_types=1);

namespace App\Lesson01\Rotate;

readonly class Direction
{
    public function __construct(
        private int $direction,
        private int $directionsNumber
    ) {}

    public function getDirection(): int
    {
        return $this->direction;
    }

    public function getDirectionsNumber(): int
    {
        return $this->directionsNumber;
    }

    public function next(int $angularVelocity): self
    {
        return new self(
            direction: ($this->getDirection() + $angularVelocity) % $this->getDirectionsNumber(),
            directionsNumber: $this->directionsNumber
        );
    }
}
