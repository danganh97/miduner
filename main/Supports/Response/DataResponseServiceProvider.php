<?php

namespace Main\Supports\Response;

use Main\ServiceProvider;

class DataResponseServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton('response', function () {
            return new \Main\Supports\Response\DataResponse;
        });
    }
}
