<?php

declare(strict_types=1);

namespace Mellanyx\Api\User;

use PDO;

class User
{
    // подключение к БД таблице "users"
    private object $conn;
    private string $table_name = 'users';

    // свойства объекта
    public int $id;
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $password;

    // конструктор класса User
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create(): bool
    {
        $query = "INSERT INTO " . $this->table_name . "
            SET
            firstname = :firstname,
            lastname = :lastname,
            email = :email,
            password = :password";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // инъекция
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // привязываем значения
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);

        // для защиты пароля
        // хешируем пароль перед сохранением в базу данных
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        // Выполняем запрос
        // Если выполнение успешно, то информация о пользователе будет сохранена в базе данных
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function userExist(): bool
    {
        $query = "SELECT `id`, `email` FROM " . $this->table_name . "
            WHERE
            email = :email
            LIMIT 1";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':email', $this->email);

        $stmt->execute();

        if ($stmt->execute() && !empty($stmt->fetchAll(PDO::FETCH_ASSOC))) {
            return true;
        }

        return false;
    }

    public function getUser(): array
    {
        $query = "SELECT * FROM " . $this->table_name . "
            WHERE
            email = :email
            LIMIT 1";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':email', $this->email);

        $stmt->execute();

        return current($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // здесь будет метод emailExists()
}
