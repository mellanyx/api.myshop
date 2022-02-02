<?php

declare(strict_types=1);

namespace Mellanyx\Api\JWT;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class JWTValidateController
{
    public array $jwtConfig;

    public function __construct()
    {
        $this->jwtConfig = require CONFIG_PATH . 'jwtconfig.php';
    }

    public function validateJWT(ServerRequestInterface $request): ResponseInterface
    {
        // получаем jwt токен
        $data = json_decode($request->getBody()->getContents(), true);

        $access_token = $data['access_token'] ?? "";

        // если jwt токен не пуст
        if ($access_token) {
            try {
                // декодирование jwt
                $decoded = JWT::decode($access_token, new Key($this->jwtConfig['key'], 'HS256'));

                // показать детали
                $msg = [
                  'status'  => 200,
                  'message' => 'Доступ разрешен',
                  "data" => $decoded->data
                ];
            } catch (\Throwable $e) {
                $msg = [
                  'status'  => 403,
                  'message' => 'Доступ запрещён',
                  "error" => $e->getMessage()
                ];
            }
        } else {
            $msg = [
              'status'  => 403,
              'message' => 'Доступ запрещён',
            ];
        }

        $response = new Response();
        $response->getBody()->write(json_encode($msg, JSON_UNESCAPED_UNICODE));
        return $response->withStatus($msg['status']);
    }
}
