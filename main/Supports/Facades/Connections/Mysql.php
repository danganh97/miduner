<?php

namespace Main\Supports\Facades\Connections;

use Main\Supports\Facade;

class App extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mysqlConnection';
    }
}
