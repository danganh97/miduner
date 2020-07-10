<?php

namespace Main\Traits;

trait Instance
{
    private static $instance;

    public function __construct()
    {
        self::$instance = $this;
    }

    public static function getInstance()
    {
        if(!static::$instance) {
            static::$instance = new static;
        }
        return static::$instance;
    }
}
