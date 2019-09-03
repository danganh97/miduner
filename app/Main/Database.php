<?php

namespace App\Main;

use App\Main\Registry;
use \PDO;
use \PDOException;

class Database
{
    public function __construct()
    {
        $this->config = config('database.connection');
        $connection = $this->config['driver'];
        $host = $this->config['host'];
        $database_name = $this->config['database'];
        $username = $this->config['username'];
        $password = $this->config['password'];
        $this->pdo = new PDO("$connection:host=$host;dbname=$database_name", $username, $password, null);
    }
    public function connection()
    {
        $connection = $this->config['driver'];
        $host = $this->config['host'];
        $database_name = $this->config['database'];
        $username = $this->config['username'];
        $password = $this->config['password'];
        try {
            $this->pdo->beginTransaction();
            $this->pdo->exec("set names utf8");
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->commit();
            return $this->pdo;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new \App\Main\AppException($e->getMessage());
        }
    }
}
