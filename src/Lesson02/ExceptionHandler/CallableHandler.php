<?php

declare(strict_types=1);

namespace App\Lesson02\ExceptionHandler;

use App\Lesson01\Command;
use Exception;

interface CallableHandler
{
    public function __invoke(Exception $e, Command $command): Command;
}
