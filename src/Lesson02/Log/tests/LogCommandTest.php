<?php

declare(strict_types=1);

namespace App\Lesson02\Log\tests;

use App\Lesson02\Log\LogCommand;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @internal
 */
final class LogCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $exception = new Exception('Test exception');
        $logger = $this->createMock(LoggerInterface::class);

        $logger->expects(self::once())
            ->method('error')
            ->with(self::equalTo($exception->getMessage()));

        $command = new LogCommand($exception, $logger);
        $command->execute();
    }
}
