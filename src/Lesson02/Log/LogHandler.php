<?php

declare(strict_types=1);

namespace App\Lesson02\Log;

use App\Lesson01\Command;
use App\Lesson02\ExceptionHandler\CallableHandler;
use Exception;
use Monolog\Logger;

class LogHandler implements CallableHandler
{
    public function __invoke(Exception $e, Command $command): Command
    {
        $logger = new Logger('architecture-patterns');
        return new LogToQueueCommand($e, $logger);
    }
}
