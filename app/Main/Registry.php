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
        $this->storage[$name] = !isset($this->storage[$name]) ? $value : $this->storage[$name];
    }

    public function __get($name)
    {
        return isset($this->storage[$name]) ? $this->storage[$name] : null;
    }
}
