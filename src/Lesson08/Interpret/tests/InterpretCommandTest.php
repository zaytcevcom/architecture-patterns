<?php

declare(strict_types=1);

namespace App\Lesson08\Interpret\tests;

use App\Lesson01\Command;
use App\Lesson01\Move\MoveCommand;
use App\Lesson05\IoC\IoC;
use App\Lesson07\SafeQueue\Receiver;
use App\Lesson07\SafeQueue\SafeQueue;
use App\Lesson08\Interpret\InterpretCommand;
use DomainException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class InterpretCommandTest extends TestCase
{
    /** @throws \Exception|Exception */
    protected function setUp(): void
    {
        $this->setScopes();
        $this->setGameObjects();
        $this->setGameOperations();
        $this->setCommands();

        $this->setQueue();
    }

    /** @throws \Exception */
    public function testExecute(): void
    {
        $interpretCommand = new InterpretCommand(1, 2, 3, [4]);
        $interpretCommand->execute();

        /** @var Receiver $queue */
        $queue = IoC::resolve('serverThreadSafeQueue');

        self::assertEquals(1, $queue->length());
    }

    /** @throws \Exception */
    private function setScopes(): void
    {
        /** @var Command $commandScopesNew */
        $commandScopesNew = IoC::resolve('Scopes.New', 'game-1');
        $commandScopesNew->execute();

        /** @var Command $commandScopesCurrent */
        $commandScopesCurrent = IoC::resolve('Scopes.Current', 'game-1');
        $commandScopesCurrent->execute();
    }

    /** @throws \Exception */
    private function setGameObjects(): void
    {
        /** @var Command $cmd */
        $cmd = IoC::resolve('IoC.Register', 'Game.Objects', static function () {
            return (object)[
                0 => new stdClass(),
                1 => new stdClass(),
                2 => new stdClass(),
            ];
        });
        $cmd->execute();

        /** @var Command $cmd */
        $cmd = IoC::resolve('IoC.Register', 'Game.Objects.ById', static function (mixed ...$args): object {
            $arr = (array)IoC::resolve('Game.Objects');
            $id = $args[0][0] ?? null;

            if (!\is_int($id)) {
                throw new DomainException('Invalid id');
            }

            return (object)$arr[$id];
        });
        $cmd->execute();
    }

    /** @throws \Exception */
    private function setGameOperations(): void
    {
        /** @var Command $cmd */
        $cmd = IoC::resolve('IoC.Register', 'Games.Operations', static function () {
            return (object)[
                1 => 'RotateCommand',
                3 => 'MoveCommand',
            ];
        });
        $cmd->execute();

        /** @var Command $cmd */
        $cmd = IoC::resolve('IoC.Register', 'Games.Operations.ById', static function (mixed ...$args): object {
            $arr = (array)IoC::resolve('Games.Operations');
            $id = $args[0][0] ?? null;

            if (!\is_int($id)) {
                throw new DomainException('Invalid id');
            }

            return (object)$arr[$id];
        });
        $cmd->execute();
    }

    /** @throws Exception|\Exception */
    private function setCommands(): void
    {
        $moveCommandMock = $this->createMock(MoveCommand::class);

        /** @var Command $cmd */
        $cmd = IoC::resolve('IoC.Register', 'MoveCommand', static function () use ($moveCommandMock) {
            return $moveCommandMock;
        });
        $cmd->execute();
    }

    /** @throws \Exception */
    private function setQueue(): void
    {
        $safeQueue = new SafeQueue();

        /** @var Command $cmd */
        $cmd = IoC::resolve('IoC.Register', 'serverThreadSafeQueue', static function () use (&$safeQueue) {
            return $safeQueue;
        });
        $cmd->execute();
    }
}
