<?php

declare(strict_types=1);

namespace App\Lesson07\Start;

use App\Lesson01\Command;
use App\Lesson05\IoC\IoC;
use App\Lesson07\SafeQueue\Receiver;
use App\Lesson07\ServerThread\ServerThread;
use Exception;

class StartCommand implements Command
{
    /** @throws Exception */
    public function execute(): void
    {
        /** @var Receiver $queue */
        $queue = IoC::resolve('serverThreadSafeQueue');

        $behavior = static function () use (&$queue): void {
            $command = $queue->take();

            try {
                $command->execute();
            } catch (Exception $exception) {
                /** @var Command $cmd */
                $cmd = IoC::resolve('Exception.Handler', $exception, $command);
                $cmd->execute();
            }
        };

        $serverThread = new ServerThread($behavior);

        /** @var Command $cmd */
        $cmd = IoC::resolve('IoC.Register', 'serverThread', static function () use (&$serverThread) {
            return $serverThread;
        });
        $cmd->execute();

        /** @var ServerThread $serverThread */
        $serverThread = IoC::resolve('serverThread');
        $serverThread->start();
    }
}
