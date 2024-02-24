<?php

declare(strict_types=1);

namespace App\Lesson04;

use Exception;

class QuadraticEquation
{
    public const float EPSILON = 0.00001;

    /**
     * @return float[]
     * @throws Exception
     */
    public function solve(float $a, float $b, float $c): array
    {
        if (abs($a) < self::EPSILON) {
            throw new Exception('A coefficient cannot be 0');
        }

        if (is_nan($a) || is_nan($b) || is_nan($c)) {
            throw new Exception('Coefficients must be valid numbers');
        }

        if (is_infinite($a) || is_infinite($b) || is_infinite($c)) {
            throw new Exception('Coefficients cannot be infinite');
        }

        $discriminant = $b * $b - 4 * $a * $c;

        if ($discriminant > self::EPSILON) {
            $root1 = (-$b + sqrt($discriminant)) / (2 * $a);
            $root2 = (-$b - sqrt($discriminant)) / (2 * $a);
            return [$root1, $root2];
        }
        if (abs($discriminant) < self::EPSILON) {
            $root = -$b / (2 * $a);
            return [$root];
        }

        return [];
    }
}
