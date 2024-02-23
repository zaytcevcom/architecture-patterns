<?php

declare(strict_types=1);

namespace App\Lesson02\RepeatTwice;

use App\Lesson01\Command;
use App\Lesson02\ExceptionHandler\CallableHandler;
use Exception;

class RepeatTwiceHandler implements CallableHandler
{
    public function __invoke(Exception $e, Command $command): Command
    {
        return new RepeatTwiceToQueueCommand($command);
    }
}
