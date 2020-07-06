<?php

namespace Main;

class Container
{
    private $storage = [];

    private $bindings;

    private static $instance;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function make($entity)
    {
        return $this->__get($entity);
    }

    public function singleton($entity, $singleton)
    {
        if (is_callable($singleton)) {
            $singleton = call_user_func($singleton);
        }
        $this->__set($entity, $singleton);
    }

    /**
     * Binding interface to classes
     * @param string $interface
     * @param string $concrete
     *
     * @return void
     */
    public function bind($interface, $concrete)
    {
        $this->bindings[$interface] = $concrete;
    }

    /**
     * Get list of bindings
     *
     * @return array
     */
    public function getBindings()
    {
        return $this->bindings;
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