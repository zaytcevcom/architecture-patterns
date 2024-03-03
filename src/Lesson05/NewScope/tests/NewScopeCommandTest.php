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
    private IoC $ioc;

    protected function setUp(): void
    {
        $this->ioc = new IoC();
    }

    /**
     * @template T of object
     */
    public function testNewScopeCommand(): void
    {
        $scopeId = 'testScope';
        $command = new NewScopeCommand($this->ioc, $scopeId);
        $command->execute();

        $scopeProp = new ReflectionProperty(IoC::class, 'scopes');
        /** @var array<string, array<string, callable(mixed ...$args):T>> $scopes */
        $scopes = $scopeProp->getValue($this->ioc);

        self::assertArrayHasKey($scopeId, $scopes);
    }
}
