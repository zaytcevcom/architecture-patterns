<?php

declare(strict_types=1);

namespace App\Lesson03\Macro\tests;

use App\Lesson01\Command;
use App\Lesson03\Macro\MacroCommand;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MacroCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $command1 = $this->createMock(Command::class);
        $command1->expects(self::once())->method('execute');

        $command2 = $this->createMock(Command::class);
        $command2->expects(self::once())->method('execute');

        $command3 = $this->createMock(Command::class);
        $command3->expects(self::once())->method('execute');

        $macroCommand = new MacroCommand([
            $command1,
            $command2,
            $command3,
        ]);

        $macroCommand->execute();
    }
}
