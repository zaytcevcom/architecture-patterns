<?php

declare(strict_types=1);

namespace App\Lesson03\MoveWithFuel\tests;

use App\Lesson01\Command;
use App\Lesson01\Move\MoveCommand;
use App\Lesson03\BurnFuel\BurnFuelCommand;
use App\Lesson03\CheckFuel\CheckFuelCommand;
use App\Lesson03\CommandException;
use App\Lesson03\Macro\MacroCommand;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MoveWithFuelCommandTest extends TestCase
{
    public function testExistFuel(): void
    {
        $checkFuelCommand = $this->createMock(CheckFuelCommand::class);
        $checkFuelCommand->expects(self::once())->method('execute');

        $moveCommand = $this->createMock(MoveCommand::class);
        $moveCommand->expects(self::once())->method('execute');

        $burnFuelCommand = $this->createMock(BurnFuelCommand::class);
        $burnFuelCommand->expects(self::once())->method('execute');

        $macro = new MacroCommand([
            $checkFuelCommand,
            $moveCommand,
            $burnFuelCommand,
        ]);

        $macro->execute();
    }

    public function testNoFuel(): void
    {
        $exceptionCommand = $this->createMock(Command::class);
        $exceptionCommand->method('execute')->willThrowException(new CommandException());
        $exceptionCommand->expects(self::once())->method('execute');

        $moveCommand = $this->createMock(MoveCommand::class);
        $moveCommand->expects(self::exactly(0))->method('execute');

        self::expectException(CommandException::class);

        $macro = new MacroCommand([
            $exceptionCommand,
            $moveCommand,
        ]);

        $macro->execute();
    }
}
