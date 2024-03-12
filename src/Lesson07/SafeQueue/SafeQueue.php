<?php

declare(strict_types=1);

namespace App\Lesson07\SafeQueue;

use App\Lesson01\Command;
use Exception;
use SysvSemaphore;

class SafeQueue implements Receiver
{
    /** @var Command[] */
    private array $queue = [];
    private SysvSemaphore $semaphore;

    /** @throws Exception */
    public function __construct(
        int $key = 111,
        int $maxAcquire = 1,
        int $permissions = 0666,
        bool $autoRelease = true
    ) {
        $semaphore = sem_get($key, $maxAcquire, $permissions, $autoRelease);

        if (false === $semaphore) {
            throw new Exception('Can not get semaphore');
        }
        $this->semaphore = $semaphore;
    }

    public function push(Command $command): void
    {
        $this->performWithLock(function () use ($command) {
            $this->queue[] = $command;
        });
    }

    public function pop(): ?Command
    {
        $command = null;
        $this->performWithLock(function () use (&$command) {
            if (!empty($this->queue)) {
                $command = array_shift($this->queue);
            }
        });

        return $command;
    }

    public function take(): Command
    {
        while (true) {
            $command = $this->pop();
            if ($command !== null) {
                return $command;
            }
            usleep(100000);
        }
    }

    public function length(): int
    {
        return \count($this->queue);
    }

    public function isEmpty(): bool
    {
        return $this->length() === 0;
    }

    private function performWithLock(callable $action): void
    {
        /** @psalm-suppress UnusedFunctionCall */
        sem_acquire($this->semaphore);
        $action();
        /** @psalm-suppress UnusedFunctionCall */
        sem_release($this->semaphore);
    }
}
