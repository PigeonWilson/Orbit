<?php
if (!defined('PREVENT_DIRECT_FILE_ACCESS_CONST')) die();
class Db
{
    private PDO $pdo;

    public function __construct($host, $dbname, $username, $password)
    {
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function create($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));

        $query = "INSERT INTO $table ($columns) VALUES ($values)";
        $statement = $this->pdo->prepare($query);

        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        return $statement->execute();
    }


    public function read($table, $id)
    {
        $query = "SELECT * FROM $table WHERE id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_OBJ);
    }

    public function readAll($table)
    {
        $query = "SELECT * FROM $table";
        $statement = $this->pdo->prepare($query);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function update($table, $id, $data)
    {
        $setClause = implode(', ', array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($data)));

        $query = "UPDATE $table SET $setClause WHERE id = :id";
        $statement = $this->pdo->prepare($query);

        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        return $statement->execute();
    }

    public function delete($table, $id)
    {
        $query = "DELETE FROM $table WHERE id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        return $statement->execute();
    }

    public function customWhereClause($table, $data) : array
    {
        $whereConditions = [];
        foreach ($data as $key => $value) {
            $whereConditions[] = "$key = :$key";
        }
        $whereClause = implode(' AND ', $whereConditions);

        $query = "SELECT * FROM $table WHERE $whereClause";
        $statement = $this->pdo->prepare($query);

        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
}