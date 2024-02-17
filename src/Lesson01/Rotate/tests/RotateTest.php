<?php

declare(strict_types=1);

namespace App\Lesson01\Rotate\tests;

use App\Lesson01\Rotate\Direction;
use App\Lesson01\Rotate\Rotatable;
use App\Lesson01\Rotate\RotateCommand;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RotateTest extends TestCase
{
    public function testExecute(): void
    {
        $rotatable = $this->createMock(Rotatable::class);

        $rotatable->method('getDirection')->willReturn(new Direction(3, 12));
        $rotatable->method('getAngularVelocity')->willReturn(6);

        $targetDirection = new Direction(9, 12);

        $rotatable->expects(self::once())
            ->method('setDirection')
            ->with(self::equalTo($targetDirection));

        $rotate = new RotateCommand($rotatable);
        $rotate->execute();
    }

    public function testErrorGetDirection(): void
    {
        $this->expectException(Exception::class);

        $rotatable = $this->createMock(Rotatable::class);
        $rotatable->method('getDirection')->will(self::throwException(new Exception()));

        $rotate = new RotateCommand($rotatable);
        $rotate->execute();
    }

    public function testErrorGetVelocity(): void
    {
        $this->expectException(Exception::class);

        $rotatable = $this->createMock(Rotatable::class);
        $rotatable->method('getAngularVelocity')->will(self::throwException(new Exception()));

        $rotate = new RotateCommand($rotatable);
        $rotate->execute();
    }

    public function testErrorSetPosition(): void
    {
        $this->expectException(Exception::class);

        $rotatable = $this->createMock(Rotatable::class);
        $rotatable->method('getDirection')->willReturn(new Direction(3, 12));
        $rotatable->method('getAngularVelocity')->willReturn(6);
        $rotatable->method('setDirection')->will(self::throwException(new Exception()));

        $rotate = new RotateCommand($rotatable);
        $rotate->execute();
    }
}
