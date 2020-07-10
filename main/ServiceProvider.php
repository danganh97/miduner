<?php

namespace Main;

use Main\Container;

abstract class ServiceProvider
{
    public $app;

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

    public function handle()
    {
        $this->register();
        $this->boot();
    }
}