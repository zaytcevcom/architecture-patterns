<?php

declare(strict_types=1);

namespace App\Lesson07\HardStop;

use App\Lesson01\Command;
use App\Lesson05\IoC\IoC;
use App\Lesson07\ServerThread\ServerThread;
use Exception;

class HardStopCommand implements Command
{
    /** @throws Exception */
    public function execute(): void
    {
        /** @var ServerThread $serverThread */
        $serverThread = IoC::resolve('serverThread');
        $serverThread->stop();
    }
}
