<?php

declare(strict_types=1);

namespace App\Lesson02\Log;

use App\Lesson01\Command;
use Exception;
use Psr\Log\LoggerInterface;

readonly class LogToQueueCommand implements Command
{
    public function __construct(
        private Exception $exception,
        private LoggerInterface $logger
    ) {}

    public function execute(): void
    {
        /** @psalm-var Command[] $queue */
        global $queue;

        $queue[] = new LogCommand($this->exception, $this->logger);
    }
}
