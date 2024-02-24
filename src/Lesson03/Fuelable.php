<?php

declare(strict_types=1);

namespace App\Lesson03;

interface Fuelable
{
    public function getLevel(): int;

    public function setLevel(int $level): void;

    public function getConsumption(): int;
}
