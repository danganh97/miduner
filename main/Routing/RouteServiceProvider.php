<?php

namespace Main\Routing;

use Main\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton('route', function () {
            return new \Main\Route;
        });
    }
}
