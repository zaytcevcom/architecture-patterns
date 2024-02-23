<?php

declare(strict_types=1);

namespace App\Lesson02\Repeat\tests;

use App\Lesson01\Command;
use App\Lesson02\Repeat\RepeatCommand;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RepeatCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $command = $this->createMock(Command::class);
        $command->expects(self::once())
            ->method('execute');

        $repeatCommand = new RepeatCommand($command);
        $repeatCommand->execute();
    }
}
