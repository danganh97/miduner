<?php

namespace Midun\Database\Connections\PostgreSQL;

use \PDO;
use \PDOException;
use Midun\Database\Connections\Connection as MidunConnection;

class Connection extends MidunConnection
{
    /**
     * Reset driver
     * 
     * @param string $driver
     *
     * @return void
     */
    public function setDriver(string $driver)
    {
        $connections = config("database.connections");
        if (!isset($connections[$driver])) {
            throw new PostgreConnectionException("Couldn't find driver {$driver}");
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
            new PostgrePdo("$driver:host=$host;port=$port;dbname=$database", $username, $password, null);
            return true;
        } catch (PDOException $e) {
            new PostgreConnectionException($e->getMessage());
            return false;
        }
    }

    /**
     * Make instance
     *
     * @return void
     */
    public function makeInstance()
    {
        try {
            list($driver, $host, $port, $database, $username, $password) = $this->getConfig();
            $pdo = new PostgrePdo("$driver:host=$host;port=$port;dbname=$database", $username, $password, null);
            $pdo->exec("set names utf8");
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->instance = $pdo;
        } catch (PDOException $e) {
            throw new PostgreConnectionException($e->getMessage());
        }
    }
}
