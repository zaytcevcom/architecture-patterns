<?php

declare(strict_types=1);

namespace App\Lesson07\ServerThread;

class ServerThread
{
    private bool $isStop = false;
    /** @var callable */
    private $behavior;

    public function __construct(
        callable $behavior
    ) {
        $this->behavior = $behavior;
    }

    public function start(): void
    {
        while (!$this->isStop) {
            ($this->behavior)();
        }
    }

    public function stop(): void
    {
        $this->isStop = true;
    }

    public function updateBehavior(callable $behavior): void
    {
        $this->behavior = $behavior;
    }
}
