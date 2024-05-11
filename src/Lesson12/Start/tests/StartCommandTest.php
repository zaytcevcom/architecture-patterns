<?php

declare(strict_types=1);

namespace App\Lesson12\Start\tests;

use App\Lesson01\Command;
use App\Lesson05\IoC\IoC;
use App\Lesson07\HardStop\HardStopCommand;
use App\Lesson07\SafeQueue\Receiver;
use App\Lesson07\SafeQueue\SafeQueue;
use App\Lesson07\SoftStop\SoftStopCommand;
use App\Lesson12\Commands\MoveToCommand;
use App\Lesson12\Commands\RunCommand;
use App\Lesson12\Start\StartCommand;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class StartCommandTest extends TestCase
{
    /** @throws Exception */
    protected function setUp(): void
    {
        $this->clearLogs();

        $safeQueue = new SafeQueue();

        /** @var Command $cmd */
        $cmd = IoC::resolve('IoC.Register', 'serverThreadSafeQueue', static function () use (&$safeQueue) {
            return $safeQueue;
        });
        $cmd->execute();
    }

    /** @throws Exception|\PHPUnit\Framework\MockObject\Exception */
    public function testHardStop(): void
    {
        $mockCommand = $this->createMock(Command::class);

        /** @var Receiver $queue */
        $queue = IoC::resolve('serverThreadSafeQueue');
        $queue->push($mockCommand);
        $queue->push(new HardStopCommand());
        $queue->push($mockCommand);
        $queue->push($mockCommand);

        $startServerThread = new StartCommand();
        $startServerThread->execute();

        self::assertEquals(2, $queue->length());
    }

    /** @throws Exception|\PHPUnit\Framework\MockObject\Exception */
    public function testMoveTo(): void
    {
        $mockCommand = $this->createMock(Command::class);

        /** @var Receiver $queue */
        $queue = IoC::resolve('serverThreadSafeQueue');
        $queue->push($mockCommand);
        $queue->push(new MoveToCommand());
        $queue->push($mockCommand);
        $queue->push($mockCommand);
        $queue->push(new SoftStopCommand());

        $startServerThread = new StartCommand();
        $startServerThread->execute();

        self::assertEquals(0, $queue->length());
        self::assertEquals('State MoveToCommand enabled' . PHP_EOL, $this->getLogs());
    }

    /** @throws Exception|\PHPUnit\Framework\MockObject\Exception */
    public function testRun(): void
    {
        $mockCommand = $this->createMock(Command::class);

        /** @var Receiver $queue */
        $queue = IoC::resolve('serverThreadSafeQueue');
        $queue->push($mockCommand);
        $queue->push(new MoveToCommand());
        $queue->push($mockCommand);
        $queue->push(new RunCommand());
        $queue->push($mockCommand);
        $queue->push(new SoftStopCommand());

        $startServerThread = new StartCommand();
        $startServerThread->execute();

        $expectedLogs = 'State MoveToCommand enabled' . PHP_EOL .
            'State RunCommand enabled' . PHP_EOL;

        self::assertEquals(0, $queue->length());
        self::assertEquals($expectedLogs, $this->getLogs());
    }

    private function clearLogs(): void
    {
        if (file_exists($this->logsFile())) {
            unlink($this->logsFile());
        }
    }

    private function getLogs(): string
    {
        $logs = file_get_contents($this->logsFile());

        return $logs === false ? '' : $logs;
    }

    private function logsFile(): string
    {
        return 'src/temp/logs/log.txt';
    }
}
