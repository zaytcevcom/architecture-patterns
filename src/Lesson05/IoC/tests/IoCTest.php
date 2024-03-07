<?php

declare(strict_types=1);

namespace App\Lesson05\IoC\tests;

use App\Lesson01\Command;
use App\Lesson01\Move\Movable;
use App\Lesson05\IoC\IoC;
use Exception;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class IoCTest extends TestCase
{
    /** @throws Exception */
    public function testRegisterAndResolve(): void
    {
        /** @var Command $commandRegister */
        $commandRegister = IoC::resolve('IoC.Register', 'test', static function () {
            return new stdClass();
        });
        $commandRegister->execute();

        $resolved = IoC::resolve('test');
        self::assertInstanceOf(stdClass::class, $resolved);
    }

    public function testResolveNonExistentDependencyThrowsException(): void
    {
        $this->expectException(Exception::class);
        IoC::resolve('no_exists_key');
    }

    /** @throws Exception */
    public function testNewScope(): void
    {
        /** @var Command $commandScopesNew */
        $commandScopesNew = IoC::resolve('Scopes.New', 'testScope');
        $commandScopesNew->execute();

        /** @var Command $commandScopesCurrent */
        $commandScopesCurrent = IoC::resolve('Scopes.Current', 'testScope');
        $commandScopesCurrent->execute();

        /** @var Command $commandRegister */
        $commandRegister = IoC::resolve('IoC.Register', 'test', static function () {
            return new stdClass();
        });
        $commandRegister->execute();

        self::assertInstanceOf(
            stdClass::class,
            IoC::resolve('test')
        );

        /** @var Command $commandScopesCurrent */
        $commandScopesCurrent = IoC::resolve('Scopes.Current', 'default');
        $commandScopesCurrent->execute();

        $this->expectException(Exception::class);
        IoC::resolve('test-not-resolved');
    }

    /** @throws Exception */
    public function testResolveAdapter(): void
    {
        $obj = new stdClass();
        $adapter = IoC::resolve('Adapter', Movable::class, $obj);

        /** @var class-string $className */
        $className = 'App\temp\adapters\Lesson01\Move\MovableAdapter';
        self::assertInstanceOf($className, $adapter);

        self::assertTrue(method_exists($adapter, 'getPosition'));
        self::assertTrue(method_exists($adapter, 'setPosition'));
        self::assertTrue(method_exists($adapter, 'getVelocity'));
    }
}
