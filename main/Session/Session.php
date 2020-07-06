<?php

namespace Main\Session;
session_start();
class Session
{
    public function __set($key, $value)
    {
        $_SESSION[$key] = $value;
        return;
    }

    public function __get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function unset($key)
    {
        unset($_SESSION[$key]);
        return true;
    }

    public function put($key, $value)
    {
        return $this->__set($key, $value);
    }

    public function push($key, $value)
    {
        if(isset($_SESSION[$key])) {
            array_push($_SESSION[$key], $value);
            return true;
        }
        return false;
    }

    public function get($key)
    {
        return $this->__get($key);
    }
}