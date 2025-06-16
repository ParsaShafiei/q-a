<?php

namespace Database;
use PDO;
use PDOException;

class Database{
    private $connection;
    private $options = [
        PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    ];

    private $dbhost = DB_HOST;
    private $dbUsername = DB_USERNAME;
    private $dbPassword = DB_PASSWORD;
    private $dbName = DB_NAME;


    function __construct()
    {
        try{
            $this->connection = new PDO("mysql:host=" . $this->dbhost . ";dbname=" . $this->dbName, $this->dbUsername, $this->dbPassword, $this->options);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }


    public function select($sql, $values = null)
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($values);
            return $stmt;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function insert($tableName, $fields, $values){
        try{
            $stmt = $this->connection->prepare("INSERT INTO " . $tableName . "(" . implode(',', $fields) . " , created_at) VALUES ( :" . implode(', :', $fields) . " , NOW())");
            $stmt->execute(array_combine($fields, $values));
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }

    }
    public function update($tableName, $id, $fields, $values){
        $sql = "UPDATE " . $tableName . " SET ";
        $params = [];
    
        foreach (array_combine($fields, $values) as $field => $value) {
            if ($value !== null && $value !== '') {
                $sql .= $field . " = ?, ";
                $params[] = $value;
            } else {
                $sql .= $field . " = NULL, ";
            }
        }
    
        $sql .= "updated_at = NOW() WHERE id = ?";
        $params[] = $id;
    
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    public function delete($tableName, $id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM " . $tableName . " WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
}

