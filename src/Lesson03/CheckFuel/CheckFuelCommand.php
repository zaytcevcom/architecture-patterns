<?php

declare(strict_types=1);

namespace App\Lesson03\CheckFuel;

use App\Lesson01\Command;
use App\Lesson03\CommandException;
use App\Lesson03\Fuelable;

readonly class CheckFuelCommand implements Command
{
    public function __construct(
        private Fuelable $fuelable,
    ) {}

    /** @throws CommandException */
    public function execute(): void
    {
        $level = $this->fuelable->getLevel() - $this->fuelable->getConsumption();

        if ($level < 0) {
            throw new CommandException();
        }
    }
}
