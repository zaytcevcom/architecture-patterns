<?php

declare(strict_types=1);

namespace App\Lesson05\Register\tests;

use App\Lesson05\IoC\IoC;
use App\Lesson05\Register\RegisterCommand;
use Exception;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class RegisterCommandTest extends TestCase
{
    /** @throws Exception */
    public function testExecute(): void
    {
        $command = new RegisterCommand('test', static function () {
            return new stdClass();
        });
        $command->execute();

        $resolved = IoC::resolve('test');
        self::assertInstanceOf(stdClass::class, $resolved);
    }
}
