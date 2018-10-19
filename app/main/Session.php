<?php

namespace App\Main;
session_start();
class Session
{
    public function __set($key, $value)
    {
        !isset($_SESSION[$key]) ? $_SESSION[$key] = $value : die('SESSION '. $key . ' already exists !');
        return;
    }

    public function __get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function unset_session($key)
    {
        unset($_SESSION[$key]);
        return true;
    }

    public static function __callStatic($method, $params = null)
    {
        return (new static)->$method;
    }
}