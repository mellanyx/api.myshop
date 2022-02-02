<?php

declare(strict_types=1);

namespace Mellanyx\Api\User;

// требуемые заголовки
//header("Access-Control-Allow-Origin: http://authentication-jwt/");
//header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Methods: POST");
//header("Access-Control-Max-Age: 3600");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use Laminas\Diactoros\Response;
use Mellanyx\Api\Database\Database;
use Mellanyx\Api\User\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RegisterUserController
{
    public object $db_conn;

    public object $user;

    public function __construct()
    {
        // получаем соединение с базой данных
        $database = new Database();
        $this->db_conn = $database->getConnection();

        // создание объекта 'User'
        $this->user = new User($this->db_conn);
    }

    public function createUser(ServerRequestInterface $request): ResponseInterface
    {
        // получаем данные
        $data = json_decode($request->getBody()->getContents(), true);

        // устанавливаем значения
        $this->user->firstname = $data['firstname'];
        $this->user->lastname = $data['lastname'];
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];

        // проверка на существование пользователя
        if (!$this->user->userExist()) {
            // создание пользователя
            if (
                !empty($this->user->firstname)
                && !empty($this->user->email)
                && !empty($this->user->password)
                && $this->user->create()
            ) {
                // покажем сообщение о том, что пользователь был создан
                $msg = [
                  "status"  => 200,
                  "message" => "Пользователь был создан.",
                ];
            } else {
                // сообщение, если не удаётся создать пользователя

                $msg = [
                  "status"  => 400,
                  "message" => "Ошибка при создании пользователя.",
                ];
            }
        } else {
            // сообщение, если пользователь существует

            $msg = [
              "status"  => 400,
              "message" => "Такой пользователь уже существует",
            ];
        }

        $response = new Response();
        $response->getBody()->write(json_encode($msg, JSON_UNESCAPED_UNICODE));
        return $response->withStatus($msg['status']);
    }
}
