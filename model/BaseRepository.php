<?php

namespace Model;

use PDO;
use PDOStatement;

class BaseRepository
{
    public string $tableName;

    public function __construct(
        public PDO $conn
    ) {
        $this->tableName = '';
    }

    public function create(array $data): bool
    {
        $columns = implode(", ", array_keys($data));
        $values = implode(", ", array_map(function ($key) {":".$key;}, array_keys($data)));

        $statement = $this->conn->prepare("INSERT INTO {$this->tableName} ($columns) VALUES ($values)");
        $this->bindValues($statement, $data);

        return $statement->execute();
    }

    public function read(array $condition): array
    {
        $column = array_key_first($condition);
        $value = $condition[$column];

        $statement = $this->conn->prepare("SELECT * FROM {$this->tableName} WHERE $column=:$column");
        $statement->bindValue(":".$column, $value);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteById(int $id): bool
    {
        $statement = $this->conn->prepare("DELETE FROM {$this->tableName} WHERE id=:id");
        $statement->bindValue(":id", $id);

        return $statement->execute();
    }

    public function updateById(int $id, array $changes): bool
    {
        $set = $this->formatSet($changes);
        $statement = $this->conn->prepare("UPDATE {$this->tableName} SET $set WHERE id=?");
        $this->bindValues($statement, $changes);
        
        return $statement->execute([$id]);
    }

    public function bindValues(PDOStatement $statement, array $data): void
    {
        foreach ($data as $key => $value) {
            $statement->bindValue(":".$key, $value);
        }
    }

    private function formatSet(array $data): string
    {
        return implode(", ", array_map(function($key) {"$key=:$key";}, array_keys($data)));
    }
}