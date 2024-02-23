<?php

declare(strict_types=1);

namespace App\Lesson02\Repeat\tests;

use App\Lesson01\Command;
use App\Lesson02\Repeat\RepeatToQueueCommand;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RepeatToQueueCommandTest extends TestCase
{
    private Command $command;

    protected function setUp(): void
    {
        parent::setUp();

        global $queue;
        $queue = [];

        $this->command = new RepeatToQueueCommand(
            $this->createMock(Command::class)
        );
    }

    public function testExecute(): void
    {
        $this->command->execute();

        /** @psalm-var Command[] $queue */
        global $queue;
        self::assertCount(1, $queue);
    }
}
