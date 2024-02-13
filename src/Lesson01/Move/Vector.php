<?php

declare(strict_types=1);

namespace App\Lesson01\Move;

readonly class Vector
{
    public function __construct(
        private float $x,
        private float $y
    ) {}

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public static function plus(self $position, self $velocity): self
    {
        return new self(
            x: $position->getX() + $velocity->getX(),
            y: $position->getY() + $velocity->getY()
        );
    }
}
