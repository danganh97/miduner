<?php

namespace Main\Supports\Facades;

use Main\Supports\Facade;

class Repository extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'repository';
    }
}
