<?php

namespace App\Providers;

use Midun\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton('hello', function () {
            return '???';
        });
    }
}