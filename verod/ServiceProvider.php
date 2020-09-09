<?php

namespace Midun;

use Midun\Container;

abstract class ServiceProvider
{
    /**
     * Instance of \Midun\Container
     * 
     * @var \Midun\Container
     */
    protected $app;

    /**
     * Initial constructor
     * 
     * @var \Midun\Container $app
     */
    public function __construct()
    {
        $this->app = Container::getInstance();
    }

    /**
     * Run after the application already registered service,
     * if you want to use 3rd or outside service,
     * please implement them to the boot method.
     */
    abstract public function boot();

    /**
     * Register all of the service providers that you
     * import in config/app.php -> providers
     */
    abstract public function register();
}