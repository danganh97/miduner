<?php

namespace Main\Session;

use Main\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton('session', function () {
            return new \Main\Session\Session;
        });
    }
}
