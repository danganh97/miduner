<?php

namespace Main\Http;

use Main\ServiceProvider;

class RequestServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton('request', function () {
            return new \Main\Http\Request;
        });
    }
}
