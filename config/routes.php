<?php

declare(strict_types=1);

use League\Route\RouteGroup;
use Mellanyx\Api\HomepageController;
use Mellanyx\Api\JWT\JWTValidateController;
use Mellanyx\Api\User\LoginUserController;
use Mellanyx\Api\User\RegisterUserController;

$responseFactory = new \Laminas\Diactoros\ResponseFactory();
$strategy = new \League\Route\Strategy\JsonStrategy($responseFactory);

return function (League\Route\Router $router) use ($strategy) {
    $router->get('/', [HomepageController::class, 'index'])
      ->setStrategy($strategy);

    $router->group('/api', function (RouteGroup $route) {
        $route->map('POST', '/createUser', [RegisterUserController::class, 'createUser']);
        $route->map('POST', '/authUser', [LoginUserController::class, 'authUser']);
        $route->map('POST', '/validateToken', [JWTValidateController::class, 'validateJWT']);
    })->setStrategy($strategy);
};
