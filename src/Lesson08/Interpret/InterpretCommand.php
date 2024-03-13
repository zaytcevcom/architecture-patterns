<?php

declare(strict_types=1);

namespace App\Lesson08\Interpret;

use App\Lesson01\Command;
use App\Lesson05\IoC\IoC;
use App\Lesson07\SafeQueue\Receiver;
use Exception;

readonly class InterpretCommand implements Command
{
    public function __construct(
        private int $gameId,
        private int $objectId,
        private int $operationId,
        private array $args
    ) {}

    /** @throws Exception */
    public function execute(): void
    {
        /** @var Command $commandScopesCurrent */
        $commandScopesCurrent = IoC::resolve('Scopes.Current', 'game-' . $this->gameId);
        $commandScopesCurrent->execute();

        $object = IoC::resolve('Game.Objects.ById', $this->objectId);
        $operation = IoC::resolve('Games.Operations.ById', $this->operationId);

        /** @var Command $command */
        $command = IoC::resolve((string)$operation->scalar, $object, ...$this->args);

        /** @var Receiver $queue */
        $queue = IoC::resolve('serverThreadSafeQueue');
        $queue->push($command);
    }
}
