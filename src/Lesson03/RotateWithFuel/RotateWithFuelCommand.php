<?php

declare(strict_types=1);

namespace App\Lesson03\RotateWithFuel;

use App\Lesson01\Move\Movable;
use App\Lesson01\Rotate\Rotatable;
use App\Lesson01\Rotate\RotateCommand;
use App\Lesson03\BurnFuel\BurnFuelCommand;
use App\Lesson03\ChangeVelocity\ChangeVelocityCommand;
use App\Lesson03\CheckFuel\CheckFuelCommand;
use App\Lesson03\Fuelable;
use App\Lesson03\Macro\MacroCommand;

readonly class RotateWithFuelCommand
{
    public function __construct(
        private Movable $movable,
        private Fuelable $fuelable,
        private Rotatable $rotatable,
    ) {}

    public function execute(): void
    {
        $macro = new MacroCommand([
            new CheckFuelCommand($this->fuelable),
            new RotateCommand($this->rotatable),
            new ChangeVelocityCommand($this->movable, $this->rotatable),
            new BurnFuelCommand($this->fuelable),
        ]);

        $macro->execute();
    }
}
