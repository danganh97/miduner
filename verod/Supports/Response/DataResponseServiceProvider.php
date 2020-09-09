<?php

namespace Midun\Supports\Response;

use Midun\ServiceProvider;

class DataResponseServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton('response', function () {
            return new \Midun\Supports\Response\DataResponse;
        });
    }
}
