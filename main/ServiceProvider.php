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

    abstract public function boot();

    abstract public function register();

    public function handle()
    {
        $this->register();
    }
}