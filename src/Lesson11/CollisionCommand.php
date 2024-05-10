<?php

declare(strict_types=1);

namespace App\Lesson11;

use App\Lesson01\Command;
use App\Lesson01\Move\Movable;
use App\Lesson01\Move\Vector;
use App\Lesson03\Macro\MacroCommand;
use App\Lesson05\IoC\IoC;
use App\Lesson11\CheckCollision\CheckCollisionCommand;
use App\Lesson11\GameField\GameField;
use App\Lesson11\GameField\Sector;
use Exception;

readonly class CollisionCommand implements Command
{
    public function __construct(
        private Movable $object
    ) {}

    /** @throws Exception */
    public function execute(): void
    {
        /** @var GameField $gameField */
        $gameField = IoC::resolve('GameField');

        $currentSector = $gameField->findSector($this->object->getPosition());
        $prevSector = $gameField->findSector($this->getPrevPosition());

        if (!$this->isEqualSector($currentSector, $prevSector)) {
            $gameField->removeObject($prevSector, $this->object);
            $gameField->addObject($currentSector, $this->object);

            /** @var Command[] $commands */
            $commands = [];

            foreach ($gameField->getObjects($currentSector) as $object) {
                $commands[] = new CheckCollisionCommand($this->object, $object);
            }

            $_macroCommand = new MacroCommand($commands);

            /**
             * todo.
             *
             * Условия задания:
             * Если объект попал в новую окрестность, то удаляет его из списка объектов старой окрестности и добавляет список объектов новой окрестности.
             * Для каждого объекта новой окрестности и текущего движущегося объекта создает команду проверки коллизии этих двух объектов.
             * Все эти команды помещает в макрокоманду и эту макрокоманду записывает на место аналогичной макрокоманды для предыдущей окрестности.
             *
             * Вопросы:
             * 1. Правильно понимаю, что GameField и CheckCollisionCommand принимают только объекты c интерфейсом Movable или нужен базовый object?
             * 2. Если не попали в новую окрестность, то тоже создавать макрокоманду с проверкой коллизий для всех объектов окрестности?
             * 3. Макрокоманду перезаписывать через IoC или тут как раз используется CoRHandler?
             * 4. Подходит ли для определения предыдущей позиции объекта метод getPrevPosition() реализованный в данном классе ?
             * 5. Для реализации механизма с произвольным количеством систем окрестностей нужно тоже использовать CoRHandler?
             */
        }
    }

    private function getPrevPosition(): Vector
    {
        $currentPosition = $this->object->getPosition();

        return Vector::plus(
            $currentPosition,
            new Vector(
                -1 * $currentPosition->getX(),
                -1 * $currentPosition->getY(),
            )
        );
    }

    private function isEqualSector(Sector $current, Sector $prev): bool
    {
        return $current->getRow() === $prev->getRow() && $current->getCol() === $prev->getCol();
    }
}
