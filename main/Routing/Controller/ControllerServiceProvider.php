<?php

namespace Main\Routing\Controller;

use Main\ServiceProvider;

class ControllerServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton('controller', function () {
            return new \Main\Routing\Controller\Controller;
        });
    }
}
