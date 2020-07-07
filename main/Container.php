<?php

namespace Main;

class Container
{
    /**
     * Storage saving registry variables
     * @var array $storage
     */
    private $storage = [];

    /**
     * Storage saving bindings objects
     * @var array $bindings
     */
    private $bindings;

    /**
     * The instance of the container
     * @var self $instance
     */
    private static $instance;

    /**
     * Get instance of the container
     * @return self
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     *
     * Make a entity
     * @param string $entity
     * @return mixed
     */
    public function make($entity)
    {
        return $this->__get($entity);
    }

    /**
     *
     * Make a entity
     * @param string $entity
     * @return mixed
     */
    public function resolve($entity)
    {
        return $this->make($entity);
    }

    /**
     * Register a concrete to abstract
     * @param string $entity
     * @param mixed $singleton
     * @return void
     */
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

    /**
     * Setter
     */
    public function __set($name, $value)
    {
        $this->storage[$name] = !isset($this->storage[$name]) ? $value : $this->storage[$name];
    }

    /**
     * Getter
     */
    public function __get($name)
    {
        return isset($this->storage[$name]) ? $this->storage[$name] : null;
    }
}
