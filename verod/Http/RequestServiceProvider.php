<?php

namespace Midun\Http;

use Midun\ServiceProvider;

class RequestServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton('request', function () {
            return new \Midun\Http\Request;
        });
    }
}
