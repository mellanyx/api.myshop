<?php

declare(strict_types=1);

namespace Mellanyx\Api\JWT;

use Firebase\JWT\JWT;

class JWTCreateController
{
    public array $jwtConfig;

    public bool $user_exists;
    public object $user;
    public string $data_password;

    public function __construct(object $user, $data_password)
    {
        $this->jwtConfig = require CONFIG_PATH . 'jwtconfig.php';
        $this->user = $user;
        $this->user_exists = $user->userExist();
        $this->data_password = $data_password;
    }

    public function createJWT(): array
    {
        // существует ли юзер и соответствует ли пароль тому, что находится в базе данных
        if (
            $this->user_exists
            && password_verify($this->data_password, $this->user->password)
        ) {
            $payload = [
              'iss' => $this->jwtConfig['iss'],
              'aud' => $this->jwtConfig['aud'],
              'iat' => $this->jwtConfig['iat'],
              'nbf' => $this->jwtConfig['nbf'],
              'data' => [
                'id' => $this->user->id,
                'firstname' => $this->user->firstname,
                'lastname' => $this->user->lastname,
                'email' => $this->user->email
              ]
            ];

            // создание jwt
            try {
                $access_token = JWT::encode($payload, $this->jwtConfig['key'], 'HS256');
                $data = ['status' => true, 'access_token' => $access_token];
            } catch (\Throwable $e) {
                $data = ['status' => false, 'error' => $e->getMessage()];
            }

            return $data;
        }

        return [
            'status' => false,
            'error' => 'Ошибка входа'
        ];
    }
}
