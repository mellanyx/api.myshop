<?php

declare(strict_types=1);

namespace Mellanyx\Api\Database;

use PDO;
use PDOException;

class Database
{
    public array $db;
    public $conn;

    public function __construct()
    {
        $this->db = require CONFIG_PATH . 'dbconfig.php';
    }

    public function getConnection(): object
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->db['host'] . ";dbname=" . $this->db['db_name'], $this->db['username'], $this->db['password']);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
