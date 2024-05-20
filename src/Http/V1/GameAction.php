<?php

declare(strict_types=1);

namespace App\Http\V1;

use App\Components\Serializer\Denormalizer;
use App\Helpers\OpenApi\ResponseSuccessful;
use App\Helpers\OpenApi\Security;
use App\Http\Middleware\Identity\Authenticate;
use App\Http\Response\JsonResponse;
use App\Lesson01\Command;
use App\Lesson05\IoC\IoC;
use App\Lesson07\SafeQueue\Receiver;
use App\Lesson13\InterpretWithPermission\InterpretWithPermissionCommand;
use Exception;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

#[OA\Post(
    path: '/game',
    description: 'Взаимодействие с игровым сервером',
    summary: 'Взаимодействие с игровым сервером',
    security: [Security::BEARER_AUTH],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'gameId',
                    type: 'integer',
                    example: 1,
                ),
                new OA\Property(
                    property: 'objectId',
                    type: 'integer',
                    example: 10,
                ),
                new OA\Property(
                    property: 'operationId',
                    type: 'integer',
                    example: 2,
                ),
                new OA\Property(
                    property: 'args',
                    type: 'array',
                    items: new OA\Items(),
                    example: [],
                ),
            ]
        )
    ),
    tags: ['Game'],
    responses: [new ResponseSuccessful()]
)]
final readonly class GameAction implements RequestHandlerInterface
{
    public function __construct(
        private Denormalizer $denormalizer,
    ) {}

    /** @throws Exception|ExceptionInterface */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $identity = Authenticate::getIdentity($request);

        $data = $request->getParsedBody();

        $command = $this->denormalizer->denormalize(
            data: array_merge((array)$data, ['userId' => $identity->id]),
            type: InterpretWithPermissionCommand::class
        );

        $gameId = $data['gameId'] ?? null;

        if (!\is_int($gameId)) {
            throw new Exception('Invalid gameId');
        }

        if ($gameId !== $identity->gameId) {
            throw new Exception('Access denied for this game!');
        }

        $this->addedToQueue($gameId, $command);

        return new JsonResponse(1);
    }

    /** @throws Exception */
    private function addedToQueue(int $gameId, InterpretWithPermissionCommand $command): void
    {
        /** @var Command $commandScopesCurrent */
        $commandScopesCurrent = IoC::resolve('Scopes.Current', 'game-' . $gameId);
        $commandScopesCurrent->execute();

        /** @var Receiver $queue */
        $queue = IoC::resolve('serverThreadSafeQueue');
        $queue->push($command);
    }
}
