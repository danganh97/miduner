<?php

namespace App\Main;

use App\Main\Registry;
use \PDO;
use \PDOException;

class Database
{
    public function connection()
    {
        $database = config('DATABASE');
        $connection = $database['DB_CONNECTION'];
        $host = $database['DB_HOST'];
        $database_name = $database['DB_DATABASE'];
        $username = $database['DB_USERNAME'];
        $password = $database['DB_PASSWORD'];
        try {
            $conn = new PDO("$connection:host=$host;dbname=$database_name", $username, $password);
            $conn->exec("set names utf8");
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
