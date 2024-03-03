<?php

declare(strict_types=1);

namespace App\Lesson05\Register;

use App\Lesson01\Command;
use App\Lesson05\IoC\IoC;

/**
 * @template T of object
 */
class RegisterCommand implements Command
{
    public function __construct(
        private readonly IoC $ioc,
        private readonly string $key,
        /** @var callable(mixed ...$args):T */
        private $callback
    ) {}

    public function execute(): void
    {
        $this->ioc->register($this->key, $this->callback);
    }
}
