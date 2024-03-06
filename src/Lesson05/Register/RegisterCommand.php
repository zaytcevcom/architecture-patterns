<?php

declare(strict_types=1);

namespace App\Lesson05\Register;

use App\Lesson01\Command;
use App\Lesson05\IoC;

class RegisterCommand implements Command
{
    public function __construct(
        private readonly string $key,
        /** @var callable(mixed ...$args):object */
        private $callback
    ) {}

    public function execute(): void
    {
        IoC\IoC::register($this->key, $this->callback);
    }
}
