<?php

declare(strict_types=1);

namespace App\Lesson01\Move\tests;

use App\Lesson01\Move\Movable;
use App\Lesson01\Move\MoveCommand;
use App\Lesson01\Move\Vector;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MoveTest extends TestCase
{
    public function testExecute(): void
    {
        $movable = $this->createMock(Movable::class);

        $movable->method('getPosition')->willReturn(new Vector(12, 5));
        $movable->method('getVelocity')->willReturn(new Vector(-7, 3));

        $targetVector = new Vector(5, 8);

        $movable->expects(self::once())
            ->method('setPosition')
            ->with(self::equalTo($targetVector));

        $move = new MoveCommand($movable);
        $move->execute();
    }

    public function testErrorGetPosition(): void
    {
        $this->expectException(Exception::class);

        $movable = $this->createMock(Movable::class);
        $movable->method('getPosition')->will(self::throwException(new Exception()));

        $move = new MoveCommand($movable);
        $move->execute();
    }

    public function testErrorGetVelocity(): void
    {
        $this->expectException(Exception::class);

        $movable = $this->createMock(Movable::class);
        $movable->method('getVelocity')->will(self::throwException(new Exception()));

        $move = new MoveCommand($movable);
        $move->execute();
    }

    public function testErrorSetPosition(): void
    {
        $this->expectException(Exception::class);

        $movable = $this->createMock(Movable::class);
        $movable->method('getPosition')->willReturn(new Vector(12, 5));
        $movable->method('getVelocity')->willReturn(new Vector(-7, 3));
        $movable->method('setPosition')->will(self::throwException(new Exception()));

        $move = new MoveCommand($movable);
        $move->execute();
    }
}
