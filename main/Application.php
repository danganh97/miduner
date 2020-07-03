<?php

namespace Main;

use Main\Route;

class Application
{
    private static $instance;

    private $storage;

    private $bindings = [];

    private $route;

    public function __construct($config)
    {
        self::$instance = $this;
        $this->setRouter();
        $this->registerGlobalConfig($config);
        $this->registerServiceProvider($config['providers']);
    }

    /**
     * Set router
     *
     * @return void
     */
    private function setRouter()
    {
        $this->route = new Route();
    }

    /**
     * Register global config
     *
     * @return void
     */
    private function registerGlobalConfig($config)
    {
        $this->singleton('config', $config);
        $this->singleton('route', $this->route);
    }

    /**
     * Run the Application
     *
     * @return mixed
     */
    public function run()
    {
        return $this->route->run();
    }

    public function terminate()
    {
        unset($this->storage);
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            die('Wrong way to start application');
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

    private function registerServiceProvider($providers = [])
    {
        foreach ($providers as $provider) {
            $provider = new $provider;
            $provider->handle();
        }
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
