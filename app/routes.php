<?php

declare(strict_types=1);

use App\Application\Actions\Home\HomeAction;
use App\Application\Controllers\UserController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return  function (App $app): void {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        return $response;
    });

    $app->group('/api', function(Group $group) {
        $group->group('/', function (Group $group) {
            $group->get('', HomeAction::class);
        });
    
        $group->group('/users', function (Group $group) {
            $group->get('', [UserController::class, 'list']);
            $group->post('', [UserController::class, 'store']);
            $group->get('/{id}', [UserController::class, 'show']);
        });
    });
    
};
