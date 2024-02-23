<?php

declare(strict_types=1);

namespace App\Lesson02\ExceptionHandler;

use App\Lesson01\Command;
use Exception;
use RuntimeException;

class ExceptionHandler
{
    /** @var array<string, array<string, CallableHandler>> */
    private static array $store = [];

    public static function register(string $commandType, string $exceptionType, CallableHandler $handler): void
    {
        self::$store[$commandType][$exceptionType] = $handler;
    }

    public static function purge(): void
    {
        self::$store = [];
    }

    public static function handle(Exception $exception, Command $command): Command
    {
        $exceptionType = \get_class($exception);
        $commandType = \get_class($command);

        if (isset(self::$store[$commandType][$exceptionType])) {
            return self::$store[$commandType][$exceptionType]($exception, $command);
        }

        $baseExceptionType = '*';
        $baseCommandType = '*';

        if (isset(self::$store[$commandType][$baseExceptionType])) {
            return self::$store[$commandType][$baseExceptionType]($exception, $command);
        }

        if (isset(self::$store[$baseCommandType][$exceptionType])) {
            return self::$store[$baseCommandType][$exceptionType]($exception, $command);
        }

        if (isset(self::$store[$baseCommandType][$baseExceptionType])) {
            return self::$store[$baseCommandType][$baseExceptionType]($exception, $command);
        }

        throw new RuntimeException('Handler not found for ' . $commandType . ' and ' . $exceptionType);
    }
}
