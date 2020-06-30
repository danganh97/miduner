<?php

namespace App\Providers;

use Main\ServiceProvider;

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