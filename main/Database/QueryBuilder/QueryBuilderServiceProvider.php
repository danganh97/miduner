<?php

namespace Main\Database\QueryBuilder;

use Main\ServiceProvider;

class QueryBuilderServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton('db', function () {
            return new \Main\Database\QueryBuilder\QueryBuilder;
        });
    }
}
