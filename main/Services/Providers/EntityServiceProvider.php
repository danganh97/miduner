<?php

namespace Main\Services\Providers;

use Main\ServiceProvider;

class EntityServiceProvider extends ServiceProvider
{
    public function boot()
    {
        
    }

    public function register()
    {
        $this->app->singleton('request', function() {
            return new \App\Http\Requests\Request;
        });
    }
}