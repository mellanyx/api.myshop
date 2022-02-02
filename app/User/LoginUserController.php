<?php

declare(strict_types=1);

namespace Mellanyx\Api\User;

use Laminas\Diactoros\Response;
use Mellanyx\Api\Database\Database;
use Mellanyx\Api\JWT\JWTCreateController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginUserController
{
    public object $db_conn;

    public object $user;

    public object $jwt;

    public array $jwtData;

    public function __construct()
    {
        // получаем соединение с базой данных
        $database = new Database();
        $this->db_conn = $database->getConnection();

        // создание объекта 'User'
        $this->user = new User($this->db_conn);
    }

    public function authUser(ServerRequestInterface $request): ResponseInterface
    {
        // получаем данные
        $data = json_decode($request->getBody()->getContents(), true);

        $this->user->email = $data['email'];

        $userData = $this->user->getUser();

        $this->user->id = (int)$userData['id'];
        $this->user->firstname = $userData['firstname'];
        $this->user->lastname = $userData['lastname'];
        $this->user->password = $userData['password'];

        $this->jwt = new JWTCreateController($this->user, $data['password']);

        $this->jwtData = $this->jwt->createJWT();

        if ($this->jwtData['status']) {
            $msg = [
              'status'  => 200,
              'message' => 'Успешная авторизация',
              'jwt' => $this->jwtData
            ];
        } else {
            $msg = [
              'status'  => 401,
              'message' => 'Ошибка авторизации',
              'jwt' => $this->jwtData
            ];
        }

        $response = new Response();
        $response->getBody()->write(json_encode($msg, JSON_UNESCAPED_UNICODE));
        return $response->withStatus($msg['status']);
    }
}
