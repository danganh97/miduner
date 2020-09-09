<?php

namespace Midun\Supports\Facades;

use Midun\Supports\Facade;

class Hustle extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Midun\Contracts\Console\Kernel::class;
    }
}
