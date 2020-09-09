<?php

namespace Midun\Session;

session_start();
class Session
{
    /**
     * Check exists session key
     * 
     * @param string $key
     * 
     * @return bool
     */
    public function isset(string $key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Set a session
     * 
     * @param string $key
     * @param mixed $value
     * 
     * @return void
     */
    public function set(string $key, $value)
    {
        return $_SESSION[$key] = $value;
    }

    /**
     * Unset a session
     * 
     * @param string $key
     * 
     * return void
     */
    public function unset(string $key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Get session by key
     * 
     * @param string $key
     * 
     * @return mixed|null
     */
    public function get(string $key)
    {
        return $this->isset($key) ? $_SESSION[$key] : null;
    }

    /**
     * Get all session storage
     * 
     * @return array|null
     */
    public function storage()
    {
        return $_SESSION;
    }
}
