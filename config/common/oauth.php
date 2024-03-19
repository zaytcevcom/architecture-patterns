<?php

declare(strict_types=1);

use App\Http\Middleware\Identity\BearerTokenValidator;
use App\Lesson09\AccessTokenRepository;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\ResourceServer;
use Psr\Container\ContainerInterface;

return [
    AccessTokenRepositoryInterface::class => DI\get(AccessTokenRepository::class),
    ResourceServer::class => static function (ContainerInterface $container): ResourceServer {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *    public_key_path:string
         * } $config
         */
        $config = $container->get('config')['oauth'];

        $repository = $container->get(AccessTokenRepositoryInterface::class);
        $publicKey = new CryptKey($config['public_key_path'], null, false);

        $validator = new BearerTokenValidator();
        $validator->setPublicKey($publicKey);

        return new ResourceServer(
            $repository,
            $publicKey,
            $validator
        );
    },
    'config' => [
        'oauth' => [
            'public_key_path' => file_get_contents(__DIR__ . '/../../docker/development/secrets/jwt_public_key'),
        ],
    ],
];
