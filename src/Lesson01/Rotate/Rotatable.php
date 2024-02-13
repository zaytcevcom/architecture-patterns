<?php

declare(strict_types=1);

namespace App\Lesson01\Rotate;

interface Rotatable
{
    public function getDirection(): Direction;

    public function setDirection(Direction $direction): void;

    public function getAngularVelocity(): int;
}
