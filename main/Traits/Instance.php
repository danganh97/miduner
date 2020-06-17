<?php

namespace Main\Traits;

trait Instance
{
    public static $instance;

    public static function getInstance()
    {
        if(!static::$instance) {
            static::$instance = new static;
        }
        return static::$instance;
    }
}
