<?php

declare(strict_types=1);

namespace App\Lesson04\tests;

use App\Lesson04\QuadraticEquation;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class QuadraticEquationTest extends TestCase
{
    private QuadraticEquation $quadraticEquation;

    protected function setUp(): void
    {
        $this->quadraticEquation = new QuadraticEquation();
    }

    /** @throws Exception */
    public function testNoRoots(): void
    {
        self::assertCount(0, $this->quadraticEquation->solve(1, 0, 1));
    }

    /** @throws Exception */
    public function testTwoRoots(): void
    {
        $result = $this->quadraticEquation->solve(1, 0, -1);
        self::assertCount(2, $result);
        self::assertEquals([1, -1], $result);
    }

    /** @throws Exception */
    public function testOneDoubleRoot(): void
    {
        $result = $this->quadraticEquation->solve(1, 2, 1);
        self::assertCount(1, $result);
        self::assertEquals([-1], $result);
    }

    public function testThrowsExceptionCoefficientIsZero(): void
    {
        $this->expectException(Exception::class);
        $this->quadraticEquation->solve(0, 2, 1);
    }

    public function testThrowsExceptionCoefficientIsInvalidNumber(): void
    {
        $this->expectException(Exception::class);
        $this->quadraticEquation->solve(NAN, 2, 1);
    }

    public function testThrowsExceptionCoefficientIsInfinite(): void
    {
        $this->expectException(Exception::class);
        $this->quadraticEquation->solve(INF, 2, 1);
    }

    public function testThrowsExceptionCoefficientIsNegativeInfinite(): void
    {
        $this->expectException(Exception::class);
        $this->quadraticEquation->solve(-INF, 2, 1);
    }
}
