<?php

declare(strict_types=1);

namespace App\Lesson05\IoC;

use App\Lesson05\CurrentScope\CurrentScopeCommand;
use App\Lesson05\NewScope\NewScopeCommand;
use App\Lesson05\Register\RegisterCommand;
use Exception;

/**
 * @template T of object
 */
class IoC
{
    private string $currentScope = 'default';
    /** @var array<string, array<string, callable(mixed ...$args):T>> */
    private array $scopes = [];

    /**
     * @psalm-param class-string<T>|string $key
     * @psalm-return T
     * @throws Exception
     */
    public function resolve(string $key, mixed ...$args): mixed
    {
        if ($key === 'IoC.Register') {
            if (!isset($args[0], $args[1])) {
                throw new Exception('Invalid arguments!');
            }

            if (!\is_string($args[0]) || !\is_callable($args[1])) {
                throw new Exception('Invalid type arguments!');
            }

            /**
             * @var callable(mixed ...$args):T $args[1]
             */
            $callback = $args[1];

            /** @var T */
            return new RegisterCommand($this, $args[0], $callback);
        }
        if ($key === 'Scopes.New') {
            if (!isset($args[0])) {
                throw new Exception('Invalid arguments!');
            }
            if (!\is_string($args[0])) {
                throw new Exception('Invalid type arguments!');
            }

            /** @var T */
            return new NewScopeCommand($this, $args[0]);
        }
        if ($key === 'Scopes.Current') {
            if (!isset($args[0])) {
                throw new Exception('Invalid arguments!');
            }
            if (!\is_string($args[0])) {
                throw new Exception('Invalid type arguments!');
            }

            /** @var T */
            return new CurrentScopeCommand($this, $args[0]);
        }
        if (isset($this->scopes[$this->currentScope][$key])) {
            return $this->scopes[$this->currentScope][$key](...$args);
        }

        throw new Exception('Dependency not found!');
    }

    public function getCurrentScope(): string
    {
        return $this->currentScope;
    }

    public function setCurrentScope(string $currentScope): void
    {
        $this->currentScope = $currentScope;
    }

    public function newScope(string $scopeId): void
    {
        $this->scopes[$scopeId] = [];
    }

    /** @param callable(mixed ...$args):T $callback */
    public function register(string $key, callable $callback): void
    {
        $this->scopes[$this->getCurrentScope()][$key] = $callback;
    }
}
