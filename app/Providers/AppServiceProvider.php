<?php

namespace App\Providers;

use Midun\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

    }

    public function register(): void
    {
        $this->app->singleton('hello', function () {
            return '???';
        });
    }
}