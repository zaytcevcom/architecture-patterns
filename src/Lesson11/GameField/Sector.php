<?php

declare(strict_types=1);

namespace App\Lesson11\GameField;

readonly class Sector
{
    public function __construct(
        private int $row,
        private int $col,
    ) {}

    public function getRow(): int
    {
        return $this->row;
    }

    public function getCol(): int
    {
        return $this->col;
    }
}
