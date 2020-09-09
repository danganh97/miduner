<?php

namespace Midun\Database\Connections;

abstract class Connection implements \Midun\Contracts\Database\Connection
{
    /**
     * Driver database connection
     */
    protected $driver;

    /**
     * Instance of connection
     */
    protected $instance;

    /**
     * Initial constructor connection
     */
    public function __construct()
    {
        $this->setDriver(
            $this->getDefaultDriver()
        );
    }

    /**
     * Get list configuration from cache file
     * 
     * @return array
     */
    public function getConfig()
    {
        $driver = $this->getDriverConnection(
            $this->driver()
        );
        $host = $this->getHostConnection(
            $this->driver()
        );
        $port = $this->getPortConnection(
            $this->driver()
        );
        $database = $this->getDatabaseConnection(
            $this->driver()
        );
        $username = $this->getUsernameConnection(
            $this->driver()
        );
        $password = $this->getPasswordConnection(
            $this->driver()
        );
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
     * @param string $driver
     *
     * @return void
     */
    abstract function setDriver(string $driver);

    /**
     * Check the connection is available
     * @return boolean
     */
    abstract function isConnected();

    /**
     * Make instance
     *
     * @return void
     */
    abstract function makeInstance();

    /**
     * Get the connection
     *
     * @return \PDOInstance
     */
    public function getConnection()
    {
        if (!$this->instance) {
            $this->makeInstance();
        }
        return $this->instance;
    }

    /**
     * Get driver
     * 
     * @return string
     */
    protected function driver()
    {
        return $this->driver;
    }

    /**
     * Get default driver
     * 
     * @return string
     */
    protected function getDefaultDriver()
    {
        return config('database.default');
    }

    /**
     * Get driver connection
     * 
     * @param string $driver
     * 
     * @return string
     */
    protected function getDriverConnection(string $driver)
    {
        return config("database.connections.{$driver}.driver");
    }

    /**
     * Get host connection
     * 
     * @param string $host
     * 
     * @return string
     */
    protected function getHostConnection(string $driver)
    {
        return config("database.connections.{$driver}.host");
    }

    /**
     * Get port connection
     * 
     * @param string $driver
     * 
     * @return string
     */
    protected function getPortConnection(string $driver)
    {
        return config("database.connections.{$driver}.port");
    }

    /**
     * Get database connection
     * 
     * @param string $driver
     * 
     * @return string
     */
    protected function getDatabaseConnection(string $driver)
    {
        return config("database.connections.{$driver}.database");
    }

    /**
     * Get username connection
     * 
     * @param string $driver
     * 
     * @return string
     */
    protected function getUsernameConnection(string $driver)
    {
        return config("database.connections.{$driver}.username");
    }

    /**
     * Get password connection
     * 
     * @param string $driver
     * 
     * @return string
     */
    protected function getPasswordConnection(string $driver)
    {
        return config("database.connections.{$driver}.password");
    }
}
