<?php

declare(strict_types=1);

namespace App\Lesson03\CheckFuel\tests;

use App\Lesson03\CheckFuel\CheckFuelCommand;
use App\Lesson03\CommandException;
use App\Lesson03\Fuelable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CheckFuelCommandTest extends TestCase
{
    /** @throws CommandException */
    public function testExecuteWithFuelLevelAboveConsumption(): void
    {
        $fuelable = $this->createMock(Fuelable::class);
        $fuelable->method('getLevel')->willReturn(10);
        $fuelable->method('getConsumption')->willReturn(5);

        $command = new CheckFuelCommand($fuelable);

        $command->execute();

        self::assertTrue(true);
    }

    /** @throws CommandException */
    public function testExecuteWithFuelLevelEqualToConsumption(): void
    {
        $fuelable = $this->createMock(Fuelable::class);
        $fuelable->method('getLevel')->willReturn(5);
        $fuelable->method('getConsumption')->willReturn(5);

        $command = new CheckFuelCommand($fuelable);

        $command->execute();

        self::assertTrue(true);
    }

    public function testExecuteWithFuelLevelBelowConsumption(): void
    {
        $fuelable = $this->createMock(Fuelable::class);
        $fuelable->method('getLevel')->willReturn(5);
        $fuelable->method('getConsumption')->willReturn(10);

        $command = new CheckFuelCommand($fuelable);

        $this->expectException(CommandException::class);

        $command->execute();
    }
}
