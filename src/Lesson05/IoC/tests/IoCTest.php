<?php

declare(strict_types=1);

namespace App\Lesson05\IoC\tests;

use App\Lesson01\Command;
use App\Lesson05\IoC\IoC;
use Exception;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class IoCTest extends TestCase
{
    private IoC $ioc;

    protected function setUp(): void
    {
        $this->ioc = new IoC();
    }

    /** @throws Exception */
    public function testRegisterAndResolve(): void
    {
        /** @var Command $commandRegister */
        $commandRegister = $this->ioc->resolve('IoC.Register', 'test', static function () {
            return new stdClass();
        });
        $commandRegister->execute();

        $resolved = $this->ioc->resolve('test');
        self::assertInstanceOf(stdClass::class, $resolved);
    }

    public function testResolveNonExistentDependencyThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->ioc->resolve('no_exists_key');
    }

    /** @throws Exception */
    public function testNewScope(): void
    {
        /** @var Command $commandScopesNew */
        $commandScopesNew = $this->ioc->resolve('Scopes.New', 'testScope');
        $commandScopesNew->execute();

        /** @var Command $commandScopesCurrent */
        $commandScopesCurrent = $this->ioc->resolve('Scopes.Current', 'testScope');
        $commandScopesCurrent->execute();

        /** @var Command $commandRegister */
        $commandRegister = $this->ioc->resolve('IoC.Register', 'test', static function () {
            return new stdClass();
        });
        $commandRegister->execute();

        self::assertInstanceOf(
            stdClass::class,
            $this->ioc->resolve('test')
        );

        /** @var Command $commandScopesCurrent */
        $commandScopesCurrent = $this->ioc->resolve('Scopes.Current', 'default');
        $commandScopesCurrent->execute();

        $this->expectException(Exception::class);
        $this->ioc->resolve('test');
    }
}
