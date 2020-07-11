<?php

namespace Main\Database\Connections\Mysql;

use Main\Http\Exceptions\AppException;
use \PDO;
use \PDOException;

class Connection
{
    /**
     * Driver database connection
     */
    private $driver;

    /**
     * Instance of connection
     */
    private $instance;

    public function __construct()
    {
        $this->driver = config('database.default');
    }

    /**
     * Get list configuration from cache file
     */
    private function getConfig()
    {
        $driver = config("database.connections.{$this->driver}.driver");
        $host = config("database.connections.{$this->driver}.host");
        $port = config("database.connections.{$this->driver}.port");
        $database = config("database.connections.{$this->driver}.database");
        $username = config("database.connections.{$this->driver}.username");
        $password = config("database.connections.{$this->driver}.password");
        return [
            $driver,
            $host,
            $port,
            $database,
            $username,
            $password,
        ];
    }

    /**
     * Reset driver
     *
     * @return void
     */
    public function setDriver($driver)
    {
        $connections = config("database.connections");
        if (!isset($connections[$driver])) {
            throw new AppException("Couldn't find driver {$driver}");
        }
        $this->driver = $driver;
        $this->makeInstance();
    }

    /**
     * Check the connection is available
     * @return boolean
     */
    public function isConnected()
    {
        try {
            list($driver, $host, $port, $database, $username, $password) = $this->getConfig();
            new PDO("$driver:host=$host;port=$port;dbname=$database", $username, $password, null);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Make instance
     *
     * @return void
     */
    private function makeInstance()
    {
        try {
            list($driver, $host, $port, $database, $username, $password) = $this->getConfig();
            $pdo = new PDO("$driver:host=$host;port=$port;dbname=$database", $username, $password, null);
            $pdo->beginTransaction();
            $pdo->exec("set names utf8");
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->commit();
            $this->instance = $pdo;
        } catch (PDOException $e) {
            if(isset($pdo) && !is_null($pdo)) {
                $pdo->rollBack();
            }
            throw new AppException($e->getMessage());
        }
    }

    /**
     * Get the connection
     *
     * @return \PDOInstance
     */
    public function getConnection()
    {
        if(!$this->instance) {
            $this->makeInstance();
        }
        return $this->instance;
    }
}
