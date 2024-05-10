<?php

declare(strict_types=1);

namespace App\Lesson11\GameField;

use App\Lesson01\Move\Movable;
use App\Lesson01\Move\Vector;
use Exception;

class GameField
{
    /** @var Movable[][][] */
    private array $sectors;
    private readonly int $sectorWidth;
    private readonly int $sectorHeight;

    public function __construct(
        private readonly int $width,
        private readonly int $height,
        private readonly int $sectorSize,
    ) {
        $this->sectorWidth = (int)($width / $sectorSize);
        $this->sectorHeight = (int)($height / $sectorSize);

        $this->sectors = array_fill(
            0,
            $this->sectorHeight,
            array_fill(0, $this->sectorWidth, [])
        );
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function findSector(Vector $position): Sector
    {
        return new Sector(
            row: (int)($position->getX() / $this->sectorSize),
            col: (int)($position->getY() / $this->sectorSize)
        );
    }

    /** @return Movable[] */
    public function getObjects(Sector $sector): array
    {
        return $this->sectors[$sector->getRow()][$sector->getCol()] ?? [];
    }

    /** @throws Exception */
    public function addObject(Sector $sector, Movable $object): void
    {
        $this->validateSector($sector);

        $this->sectors[$sector->getRow()][$sector->getCol()][] = $object;
    }

    /** @throws Exception */
    public function removeObject(Sector $sector, Movable $object): void
    {
        $this->validateSector($sector);

        /** @var Movable[] $objects */
        $objects = [];

        foreach ($this->sectors[$sector->getRow()][$sector->getCol()] as $movable) {
            if ($movable !== $object) {
                $objects[] = $movable;
            }
        }

        $this->sectors[$sector->getRow()][$sector->getCol()] = $objects;
    }

    /** @throws Exception */
    private function validateSector(Sector $sector): void
    {
        if (
            0 <= $sector->getRow() && $sector->getRow() < \count($this->sectors) &&
            0 <= $sector->getCol() && $sector->getCol() < \count($this->sectors[$sector->getRow()])
        ) {
            return;
        }

        throw new Exception('Incorrect sector');
    }
}
