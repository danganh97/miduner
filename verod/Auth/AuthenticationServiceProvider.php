<?php

namespace Midun\Auth;

use Midun\ServiceProvider;

class AuthenticationServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('auth', function () {
            return new Authenticatable;
        });
    }
}
