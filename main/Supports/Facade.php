<?php

namespace Main\Supports;

abstract class Facade
{
    protected static abstract function getFacadeAccessor();

    public static function __callStatic($method, $arguments)
    {
        return app()->make(static::getFacadeAccessor())->$method(...$arguments);
    }
}