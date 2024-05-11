<?php

declare(strict_types=1);

namespace App\Lesson12\Start;

use App\Lesson01\Command;
use App\Lesson05\IoC\IoC;
use App\Lesson07\SafeQueue\Receiver;
use App\Lesson07\ServerThread\ServerThread;
use App\Lesson12\State\BaseState;
use App\Lesson12\State\Context;
use Exception;

class StartCommand implements Command
{
    /** @throws Exception */
    public function execute(): void
    {
        /** @var Receiver $queue */
        $queue = IoC::resolve('serverThreadSafeQueue');

        $context = new Context();
        $context->setState(new BaseState());

        $behavior = static function () use ($queue, $context): void {
            $command = $queue->take();

            try {
                $context->setState(
                    $context->request($command)
                );
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
