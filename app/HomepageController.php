<?php

declare(strict_types=1);

namespace Mellanyx\Api;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomepageController
{
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()
            ->write(
                json_encode(
                    [
                        'message' => 'Это API домен'
                    ],
                    JSON_UNESCAPED_UNICODE
                )
            );
        return $response;
    }
}
