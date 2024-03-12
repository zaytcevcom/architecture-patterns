<?php

declare(strict_types=1);

namespace App\Lesson07\SoftStop;

use App\Lesson01\Command;
use App\Lesson05\IoC\IoC;
use App\Lesson07\SafeQueue\Receiver;
use App\Lesson07\ServerThread\ServerThread;
use Exception;

class SoftStopCommand implements Command
{
    /** @throws Exception */
    public function execute(): void
    {
        /** @var ServerThread $serverThread */
        $serverThread = IoC::resolve('serverThread');

        /** @var Receiver $queue */
        $queue = IoC::resolve('serverThreadSafeQueue');

        $behavior = static function () use (&$queue, &$serverThread): void {
            if ($queue->isEmpty()) {
                $serverThread->stop();
                return;
            }

            $command = $queue->take();

            try {
                $command->execute();
            } catch (Exception $exception) {
                /** @var Command $cmd */
                $cmd = IoC::resolve('Exception.Handler', $exception, $command);
                $cmd->execute();
            }
        };

        $serverThread->updateBehavior($behavior);
    }
}
