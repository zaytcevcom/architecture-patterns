<?php

declare(strict_types=1);

namespace App\Lesson07\SafeQueue\tests;

use App\Lesson01\Command;
use App\Lesson07\SafeQueue\SafeQueue;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SafeQueueTest extends TestCase
{
    private SafeQueue $safeQueue;
    private Command $command;

    /** @throws Exception */
    protected function setUp(): void
    {
        $this->command = $this->getMockBuilder(Command::class)
            ->onlyMethods(['execute'])
            ->getMock();

        $key = ftok(tempnam(sys_get_temp_dir(), 'T'), 't');
        $this->safeQueue = new SafeQueue($key);
    }

    public function testPush(): void
    {
        $this->safeQueue->push($this->command);
        self::assertEquals($this->command, $this->safeQueue->pop());
    }

    public function testPop(): void
    {
        self::assertNull($this->safeQueue->pop());
    }

    public function testTake(): void
    {
        $this->safeQueue->push($this->command);
        self::assertEquals($this->command, $this->safeQueue->take());
    }
}
