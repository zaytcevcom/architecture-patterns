<?php

declare(strict_types=1);

namespace App\Lesson11\CoR;

interface CoRHandler
{
    public function setNext(self $handler): self;

    public function handle(mixed $request): ?self;
}
