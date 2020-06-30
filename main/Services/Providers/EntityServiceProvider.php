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
        $this->app->singleton('request', function () {
            return new \App\Http\Requests\Request;
        });
        $this->app->singleton('controller', function () {
            return new \Main\Eloquent\Controller;
        });
        $this->app->singleton('response', function () {
            return new \Main\DataResponse;
        });
        $this->app->singleton('ApiResponse', function () {
            return new \Main\Http\ApiResponseResource;
        });
        $this->app->singleton('session', function () {
            return new \Main\Session;
        });
    }
}
