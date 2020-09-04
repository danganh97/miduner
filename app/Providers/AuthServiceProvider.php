<?php

namespace App\Providers;

use Midun\Auth\AuthenticationServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Booting auth service
     * 
     * @return void
     */
    public function boot()
    {
        $this->app->make('auth')->guard();
    }
}