<?php

namespace Main\Database\Connections;

use Main\ServiceProvider;

class ConnectionServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton('mysqlConnection', function () {
            return (new \Main\Database\Connections\Mysql\Connection)->getConnection();
        });
    }
}
