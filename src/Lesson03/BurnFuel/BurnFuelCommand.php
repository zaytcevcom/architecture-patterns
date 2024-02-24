<?php

declare(strict_types=1);

namespace App\Lesson03\BurnFuel;

use App\Lesson01\Command;
use App\Lesson03\Fuelable;

readonly class BurnFuelCommand implements Command
{
    public function __construct(
        private Fuelable $fuelable,
    ) {}

    public function execute(): void
    {
        $level = $this->fuelable->getLevel() - $this->fuelable->getConsumption();

        $this->fuelable->setLevel($level);
    }
}
