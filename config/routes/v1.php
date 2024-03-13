<?php

declare(strict_types=1);

use App\Components\Router\StaticRouteGroup as Group;
use App\Http\V1\GameAction;
use App\Http\V1\OpenApiAction;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->group('/v1', new Group(static function (RouteCollectorProxy $group): void {
        $group->get('', OpenApiAction::class);
        $group->post('/game', GameAction::class);
    }));
};
