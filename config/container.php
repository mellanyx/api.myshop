<?php

declare(strict_types=1);

//use App\Contacts\Controllers\ContactsController;
//use App\Homepage\Controllers\HomepageController;
//use App\System\Auth\Auth;
use Mellanyx\Api\HomepageController;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

//use function DI\create;

return [
//    'auth'                        => create(Auth::class),
    ServerRequestInterface::class => fn() => ServerRequestFactory::fromGlobals(),
    HomepageController::class     => DI\autowire(),
//    ContactsController::class     => DI\autowire()
//        ->constructorParameter('auth', function () {
//            $auth = new Auth();
//            $auth->setUser(['username' => 'Григорий']);
//            return $auth;
//        }),
];
