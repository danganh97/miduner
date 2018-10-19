<?php

namespace App\Main;

use App\Main\Registry;
use \PDO;
use \PDOException;

class Database
{
    public function connection()
    {
        $config = Registry::getInstance()->config;
        $server = $config['DATABASE']['SERVER'];
        $host = $config['DATABASE']['HOST'];
        $database = $config['DATABASE']['DATABASE_NAME'];
        $username = $config['DATABASE']['USERNAME'];
        $password = $config['DATABASE']['PASSWORD'];
        try {
            $connection = new PDO("$server:host=$host;dbname=$database", $username, $password);
            $connection->exec("set names utf8");
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
