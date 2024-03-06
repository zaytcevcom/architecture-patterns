<?php

declare(strict_types=1);

namespace App\Lesson05\NewScope\tests;

use App\Lesson05\IoC\IoC;
use App\Lesson05\NewScope\NewScopeCommand;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * @internal
 */
final class NewScopeCommandTest extends TestCase
{
    public function testNewScopeCommand(): void
    {
        $scopeId = 'testScope';
        $command = new NewScopeCommand($scopeId);
        $command->execute();

        $scopeProp = new ReflectionProperty(IoC::class, 'scopes');
        /** @var array<string, array<string, callable(mixed ...$args):object>> $scopes */
        $scopes = $scopeProp->getValue();

        self::assertArrayHasKey($scopeId, $scopes);
    }
}
