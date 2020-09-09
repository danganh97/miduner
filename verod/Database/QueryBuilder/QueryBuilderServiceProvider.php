<?php

namespace Midun\Database\QueryBuilder;

use Midun\ServiceProvider;

class QueryBuilderServiceProvider extends ServiceProvider
{
    /**
     * Booting
     * 
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register
     * 
     * @return void
     */
    public function register()
    {
        $this->app->singleton('db', function () {
            return new \Midun\Database\QueryBuilder\QueryBuilder;
        });
    }
}
