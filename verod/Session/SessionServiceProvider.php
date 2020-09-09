<?php

namespace Midun\Session;

use Midun\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton('session', function () {
            return new \Midun\Session\Session;
        });
    }
}
