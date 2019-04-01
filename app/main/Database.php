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
    }
    public function connection()
    {
        $connection = $this->config['driver'];
        $host = $this->config['host'];
        $database_name = $this->config['database'];
        $username = $this->config['username'];
        $password = $this->config['password'];
        try {
            $conn = new PDO("$connection:host=$host;dbname=$database_name", $username, $password, null);
            $conn->exec("set names utf8");
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            throw new \App\Main\AppException($e->getMessage());
        }
    }
}
