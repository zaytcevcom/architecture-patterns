<?php

declare(strict_types=1);

namespace App\Lesson02\Log\tests;

use App\Lesson01\Command;
use App\Lesson02\Log\LogCommand;
use App\Lesson02\Log\LogToQueueCommand;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @internal
 */
final class LogToQueueCommandTest extends TestCase
{
    private LogToQueueCommand $command;

    protected function setUp(): void
    {
        parent::setUp();

        global $queue;
        $queue = [];

        $exception = new Exception('Test exception');
        $logger = $this->createMock(LoggerInterface::class);

        $this->command = new LogToQueueCommand($exception, $logger);
    }

    public function testExecute(): void
    {
        $this->command->execute();

        /** @psalm-var Command[] $queue */
        global $queue;
        self::assertCount(1, $queue);
        self::assertInstanceOf(LogCommand::class, $queue[0]);
    }
}
