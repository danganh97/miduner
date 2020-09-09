<?php

namespace Midun\Routing\Controller;

use Midun\ServiceProvider;

class ControllerServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton('controller', function () {
            return new \Midun\Routing\Controller\Controller;
        });
    }
}
