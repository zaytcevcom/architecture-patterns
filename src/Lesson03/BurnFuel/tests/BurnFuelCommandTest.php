<?php

declare(strict_types=1);

namespace App\Lesson03\BurnFuel\tests;

use App\Lesson03\BurnFuel\BurnFuelCommand;
use App\Lesson03\Fuelable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class BurnFuelCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $fuelable = $this->createMock(Fuelable::class);

        $initialLevel = 100;
        $consumption = 30;

        $fuelable
            ->method('getLevel')
            ->willReturn($initialLevel);

        $fuelable
            ->method('getConsumption')
            ->willReturn($consumption);

        $fuelable
            ->expects(self::once())
            ->method('setLevel')
            ->with(self::equalTo($initialLevel - $consumption));

        $command = new BurnFuelCommand($fuelable);
        $command->execute();
    }
}
