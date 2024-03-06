<?php

declare(strict_types=1);

namespace App\Lesson05\NewScope;

use App\Lesson01\Command;
use App\Lesson05\IoC\IoC;

readonly class NewScopeCommand implements Command
{
    public function __construct(
        private string $scopeId
    ) {}

    public function execute(): void
    {
        IoC::newScope($this->scopeId);
    }
}
