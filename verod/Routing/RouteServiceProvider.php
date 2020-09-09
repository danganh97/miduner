<?php

namespace Midun\Routing;

use Midun\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Booting
     */
    public function boot()
    {

    }

    /**
     * Register singleton routing
     */
    public function register()
    {
        $this->app->singleton('route', function () {
            return new \Midun\Routing\Router;
        });
    }
}
