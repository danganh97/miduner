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
        $this->app->singleton('connection', function () {
            $default = config('database.default');
            switch (true) {
                case $default === 'mysql':
                    return new \Main\Database\Connections\Mysql\Connection;
                case $default === 'pgsql':
                    return new \Main\Database\Connections\PosgreSql\Connection;
                default:
                    throw new \RuntimeException("Driver {$default} not found.");
            }

        });
    }
}
