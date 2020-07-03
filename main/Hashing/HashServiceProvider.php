<?php

namespace Main\Hashing;

use Main\ServiceProvider;

class HashServiceProvider extends ServiceProvider
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
        $this->app->singleton('hash', function () {
            return new BcryptHasher;
        });
    }
}
