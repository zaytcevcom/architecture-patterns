<?php

declare(strict_types=1);

namespace App\Lesson01\Move;

interface Movable
{
    public function getPosition(): Vector;

    public function setPosition(Vector $vector): Vector;

    public function getVelocity(): Vector;
}
