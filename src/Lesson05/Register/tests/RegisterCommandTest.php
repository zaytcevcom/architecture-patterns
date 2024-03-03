<?php

declare(strict_types=1);

namespace App\Lesson05\Register\tests;

use App\Lesson01\Command;
use App\Lesson05\IoC\IoC;
use App\Lesson05\Register\RegisterCommand;
use Closure;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RegisterCommandTest extends TestCase
{
    private MockObject $ioc;
    private Command $command;

    protected function setUp(): void
    {
        $this->ioc = $this->createMock(IoC::class);

        $this->command = new RegisterCommand($this->ioc, 'test', static function () {
            return new class() {};
        });
    }

    public function testExecute(): void
    {
        $this->ioc->expects(self::once())
            ->method('register')
            ->with(
                self::equalTo('test'),
                self::isInstanceOf(Closure::class)
            );

        $this->command->execute();
    }
}
