<?php

namespace Model;

use PDO;

class Database
{
    public ?PDO $conn;

    public function __construct(
        private string $dbName,
        private string $dbUser,
        private string $dbPass,
        private string $dbHost
    ) {
        $this->conn = new PDO("mysql:host={$this->dbHost};dbname={$this->dbName}", $this->dbUser, $this->dbPass);
    }

    public static function createFromEnv(): self
    {
        return new self(
            $_ENV["DB_NAME"],
            $_ENV["DB_USER"],
            $_ENV["DB_PASSWORD"],
            $_ENV["DB_HOST"]
        );
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }

    public function __destruct()
    {
        $this->conn = null;
    }
}