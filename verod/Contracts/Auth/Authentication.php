<?php

namespace Midun\Contracts\Auth;

interface Authentication
{
    /**
     * Attempt an options
     * 
     * @param array $options
     * 
     * @return boolean
     */
    public function attempt(array $options = []);

    /**
     * Get user available
     * 
     * @throws \Midun\Http\Exceptions\AppException
     *
     * @return object/null
     */
    public function user();

    /**
     * Logout user
     *
     * @return void
     */
    public function logout();

    /**
     * Checking has user login
     *
     * @return boolean
     */
    public function check();

    /**
     * Set guard for authentication
     *
     * @param string $guard
     *
     * @return $this
     */
    public function guard($guard = null);
}
