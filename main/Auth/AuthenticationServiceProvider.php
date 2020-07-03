<?php

namespace Main\Auth;

use Main\ServiceProvider;

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
