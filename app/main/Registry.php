<?php

namespace App\Main;

class Registry
{
    private static $instance;
    private $storage;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function __set($name, $value)
    {
        !isset($this->storage[$name]) ? $this->storage[$name] = $value : die('This key already exists !');
    }

    public function __get($name)
    {
        return isset($this->storage[$name]) ? $this->storage[$name] : null;
    }
}
