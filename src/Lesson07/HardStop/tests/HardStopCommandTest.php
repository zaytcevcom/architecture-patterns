<?php

declare(strict_types=1);

namespace App\Lesson07\HardStop\tests;

use App\Lesson01\Command;
use App\Lesson05\IoC\IoC;
use App\Lesson07\HardStop\HardStopCommand;
use App\Lesson07\SafeQueue\Receiver;
use App\Lesson07\SafeQueue\SafeQueue;
use App\Lesson07\Start\StartCommand;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class HardStopCommandTest extends TestCase
{
    /** @throws Exception */
    protected function setUp(): void
    {
        $safeQueue = new SafeQueue();

        /** @var Command $cmd */
        $cmd = IoC::resolve('IoC.Register', 'serverThreadSafeQueue', static function () use (&$safeQueue) {
            return $safeQueue;
        });
        $cmd->execute();

        $mockCommand = $this->createMock(Command::class);

        /** @var Receiver $queue */
        $queue = IoC::resolve('serverThreadSafeQueue');
        $queue->push($mockCommand);
        $queue->push($mockCommand);
        $queue->push(new HardStopCommand());
        $queue->push($mockCommand);
    }

    /** @throws Exception */
    public function testHardStop(): void
    {
        $startServerThread = new StartCommand();
        $startServerThread->execute();

        /** @var Receiver $queue */
        $queue = IoC::resolve('serverThreadSafeQueue');

        self::assertEquals(1, $queue->length());
    }
}
