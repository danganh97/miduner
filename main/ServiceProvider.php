<?php

namespace Main;

use Main\Application;

abstract class ServiceProvider
{
    public $app;

    public function __construct()
    {
        $this->app = Application::getInstance();
    }

    abstract public function boot();

    abstract public function register();

    public function handle()
    {
        $this->register();
    }
}