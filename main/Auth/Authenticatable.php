<?php

namespace Main\Auth;

use Hash;
use Session;
use Main\Eloquent\Model;
use Main\Contracts\Auth\Authentication;
use Main\Database\QueryBuilder\QueryBuilder;

class Authenticatable implements Authentication
{
    /**
     * Guard of user
     */
    private $guard;

    /**
     * Provider of guard
     */
    private $provider;

    /**
     * Model of user
     */
    private $model;

    /**
     * Initial authentication
     */
    public function __construct()
    {
        return $this->guard();
        return $this->provider();
    }

    /**
     * Check condition and set user
     *
     * @param array $options
     *
     * @return void
     */
    public function attempt($options = [])
    {
        $columnPassword = $this->model::getInstance()->password();
        $table = $this->model::getInstance()->table();
        $paramPassword = $options[$columnPassword];
        unset($options[$columnPassword]);
        $object = QueryBuilder::table($table)->where($options)->first();
        if (!Hash::check($paramPassword, $object->password)) {
            return false;
        }
        return $this->setUSerAuth(
            $this->model::where($options)->firstOrFail()
        );
    }

    /**
     * Get user available
     *
     * @return object/null
     */
    public function user()
    {
        $guardDriver = config("auth.guards.{$this->guard}.driver");
        switch ($guardDriver) {
            case 'session':
                return Session::get('user');
                break;
            case 'passport':
                return Session::get('user_passport');
                break;
        }
    }

    /**
     * Logout user
     *
     * @return void
     */
    public function logout()
    {
        $guardDriver = config("auth.guards.{$this->guard}.driver");
        switch ($guardDriver) {
            case 'session':
                return Session::unset('user');
                break;
            case 'passport':
                return Session::unset('user_passport');
                break;
        }
    }

    /**
     * Checking has user login
     *
     * @return boolean
     */
    public function check()
    {
        if (!is_null($this->user()) && !empty($this->user())) {
            return true;
        }
        return false;
    }

    /**
     * Set user to application
     *
     * @param Model $user
     *
     * @return void
     */
    private function setUSerAuth(Model $user)
    {
        $guardDriver = config("auth.guards.{$this->guard}.driver");
        switch ($guardDriver) {
            case 'session':
                Session::put('user', $user);
                break;
            case 'passport':
                Session::put('user_passport', 'passport user');
                break;
        }
        return true;
    }

    /**
     * Set guard for authentication
     *
     * @param string $name
     *
     * @return $this
     */
    public function guard($name = null)
    {
        $this->guard = $name ?: $this->getDefaultGuard();
        $this->provider = config("auth.guards.{$this->guard}.provider");
        $this->model = config("auth.providers.{$this->provider}.model");
        return $this;
    }

    /**
     * Get default guard config
     */
    private function getDefaultGuard()
    {
        return config('auth.defaults.guard');
    }
}
