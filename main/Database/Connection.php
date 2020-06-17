<?php

namespace Main\Database;

use App\Http\Exceptions\Exception;
use \PDO;
use \PDOException;

class Connection
{
    public $config;

    public function __construct()
    {
        try {
            $this->config = config('database.connection');
            $connection = $this->config['driver'];
            $host = $this->config['host'];
            $port = $this->config['port'];
            $database_name = $this->config['database'];
            $username = $this->config['username'];
            $password = $this->config['password'];
            $this->pdo = new PDO("$connection:host=$host;port=$port;dbname=$database_name", $username, $password, null);
        } catch (\PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Get the connection
     *
     * @return \PDOInstance
     */
    public function getConnection()
    {
        try {
            $this->pdo->beginTransaction();
            $this->pdo->exec("set names utf8");
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->commit();
            return $this->pdo;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
