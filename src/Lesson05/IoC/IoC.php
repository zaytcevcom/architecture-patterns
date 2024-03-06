<?php

declare(strict_types=1);

namespace App\Lesson05\IoC;

use App\Lesson05\CurrentScope\CurrentScopeCommand;
use App\Lesson05\NewScope\NewScopeCommand;
use App\Lesson05\Register\RegisterCommand;
use Exception;

class IoC
{
    private static string $currentScope = 'default';
    /** @var array<string, array<string, callable(mixed ...$args):object>> */
    private static array $scopes = [];

    /** @throws Exception */
    public static function resolve(string $key, mixed ...$args): object
    {
        if ($key === 'IoC.Register') {
            return self::resolveRegister($args);
        }
        if ($key === 'Scopes.New') {
            return self::resolveNewScope($args);
        }
        if ($key === 'Scopes.Current') {
            return self::resolveCurrentScope($args);
        }

        return self::resolveDependency($key, $args);
    }

    public static function getCurrentScope(): string
    {
        return self::$currentScope;
    }

    public static function setCurrentScope(string $currentScope): void
    {
        self::$currentScope = $currentScope;
    }

    public static function newScope(string $scopeId): void
    {
        self::$scopes[$scopeId] = [];
    }

    /**
     * @param callable(mixed ...$args):object $callback
     */
    public static function register(string $key, callable $callback): void
    {
        self::$scopes[self::getCurrentScope()][$key] = $callback;
    }

    /** @throws Exception */
    private static function resolveRegister(array $args): RegisterCommand
    {
        if (!isset($args[0], $args[1])) {
            throw new Exception('Invalid arguments!');
        }

        if (!\is_string($args[0]) || !\is_callable($args[1])) {
            throw new Exception('Invalid type arguments!');
        }

        /** @var callable(mixed ...$args):object $args[1] */
        $callback = $args[1];

        return new RegisterCommand($args[0], $callback);
    }

    /** @throws Exception */
    private static function resolveNewScope(array $args): NewScopeCommand
    {
        if (!isset($args[0])) {
            throw new Exception('Invalid arguments!');
        }
        if (!\is_string($args[0])) {
            throw new Exception('Invalid type arguments!');
        }

        return new NewScopeCommand($args[0]);
    }

    /** @throws Exception */
    private static function resolveCurrentScope(array $args): CurrentScopeCommand
    {
        if (!isset($args[0])) {
            throw new Exception('Invalid arguments!');
        }
        if (!\is_string($args[0])) {
            throw new Exception('Invalid type arguments!');
        }

        return new CurrentScopeCommand($args[0]);
    }

    /** @throws Exception */
    private static function resolveDependency(string $key, mixed ...$args): object
    {
        if (isset(self::$scopes[self::$currentScope][$key])) {
            return self::$scopes[self::$currentScope][$key](...$args);
        }

        throw new Exception('Dependency not found!');
    }
}
