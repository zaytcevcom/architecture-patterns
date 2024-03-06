<?php

declare(strict_types=1);

namespace App\Lesson05\CurrentScope;

use App\Lesson01\Command;
use App\Lesson05\IoC\IoC;

readonly class CurrentScopeCommand implements Command
{
    public function __construct(
        private string $scopeId
    ) {}

    public function execute(): void
    {
        IoC::setCurrentScope($this->scopeId);
    }
}
