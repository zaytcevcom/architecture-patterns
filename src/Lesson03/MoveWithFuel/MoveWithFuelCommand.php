<?php

declare(strict_types=1);

namespace App\Lesson03\MoveWithFuel;

use App\Lesson01\Move\Movable;
use App\Lesson01\Move\MoveCommand;
use App\Lesson03\BurnFuel\BurnFuelCommand;
use App\Lesson03\CheckFuel\CheckFuelCommand;
use App\Lesson03\Fuelable;
use App\Lesson03\Macro\MacroCommand;

readonly class MoveWithFuelCommand
{
    public function __construct(
        private Fuelable $fuelable,
        private Movable $movable,
    ) {}

    public function execute(): void
    {
        $macro = new MacroCommand([
            new CheckFuelCommand($this->fuelable),
            new MoveCommand($this->movable),
            new BurnFuelCommand($this->fuelable),
        ]);

        $macro->execute();
    }
}
