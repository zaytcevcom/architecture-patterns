<?php

declare(strict_types=1);

namespace App\Lesson02\ExceptionHandler\tests;

use App\Lesson01\Command;
use App\Lesson02\ExceptionHandler\CallableHandler;
use App\Lesson02\ExceptionHandler\ExceptionHandler;
use App\Lesson02\Log\LogHandler;
use App\Lesson02\Repeat\RepeatCommand;
use App\Lesson02\Repeat\RepeatHandler;
use App\Lesson02\RepeatTwice\RepeatTwiceCommand;
use App\Lesson02\RepeatTwice\RepeatTwiceHandler;
use Exception;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @internal
 */
final class ExceptionHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ExceptionHandler::purge();
    }

    public function testRepeatAndLog(): void
    {
        /** @psalm-var Command[] $queue */
        global $queue;

        $commandMock = $this->createMock(Command::class);
        $commandMock->method('execute')->willThrowException(new RuntimeException());

        $commandMock->expects(self::exactly(2))
            ->method('execute');

        $queue[] = $commandMock;

        ExceptionHandler::register('*', '*', new RepeatHandler());
        ExceptionHandler::register(RepeatCommand::class, '*', new LogHandler());

        while (\count($queue) !== 0) {
            $command = array_shift($queue);
            try {
                $command->execute();
            } catch (Exception $e) {
                ExceptionHandler::handle($e, $command)->execute();
            }
        }

        self::assertCount(0, $queue, 'Queue should be empty');
    }

    public function testRepeatTwiceAndLog(): void
    {
        /** @psalm-var Command[] $queue */
        global $queue;

        $commandMock = $this->createMock(Command::class);
        $commandMock->method('execute')->willThrowException(new RuntimeException());

        $commandMock->expects(self::exactly(3))
            ->method('execute');

        $queue[] = $commandMock;

        ExceptionHandler::register('*', '*', new RepeatHandler());
        ExceptionHandler::register(RepeatCommand::class, '*', new RepeatTwiceHandler());
        ExceptionHandler::register(RepeatTwiceCommand::class, '*', new LogHandler());

        while (\count($queue) !== 0) {
            $command = array_shift($queue);
            try {
                $command->execute();
            } catch (Exception $e) {
                ExceptionHandler::handle($e, $command)->execute();
            }
        }

        self::assertCount(0, $queue, 'Queue should be empty');
    }

    public function testHandleRegistered(): void
    {
        $mockCommand = $this->createMock(Command::class);
        $mockHandler = $this->createMock(CallableHandler::class);
        $mockHandler->method('__invoke');

        $mockHandler->expects(self::once())
            ->method('__invoke');

        ExceptionHandler::register($mockCommand::class, RuntimeException::class, $mockHandler);

        $exception = new RuntimeException();
        ExceptionHandler::handle($exception, $mockCommand);
    }

    public function testHandleUnRegistered(): void
    {
        $mockCommand = $this->createMock(Command::class);
        $exception = new Exception();

        self::expectException(RuntimeException::class);

        ExceptionHandler::handle($exception, $mockCommand);
    }
}
