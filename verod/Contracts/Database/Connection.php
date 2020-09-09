<?php

namespace Midun\Contracts\Database;

interface Connection
{
    /**
     * Reset driver
     * 
     * @param string $driver
     *
     * @return void
     */
    public function setDriver(string $driver);

    /**
     * Make instance
     *
     * @return void
     */
    public function makeInstance();
}
