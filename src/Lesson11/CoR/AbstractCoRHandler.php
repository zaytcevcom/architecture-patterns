<?php

declare(strict_types=1);

namespace App\Lesson11\CoR;

class AbstractCoRHandler implements CoRHandler
{
    private ?CoRHandler $nextHandler = null;

    public function setNext(CoRHandler $handler): CoRHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(mixed $request): ?CoRHandler
    {
        return $this->nextHandler?->handle($request);
    }
}
