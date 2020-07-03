<?php

namespace Main\Auth;

use Main\Contracts\Auth\Authentication;
use Hash;
use Main\Database\QueryBuilder\QueryBuilder;

class Authenticatable implements Authentication
{
    public function attempt($options = [])
    {
        $model = config('auth.providers.default.model');
        $columnPassword = $model::getInstance()->password();
        $table = $model::getInstance()->table();
        $paramPassword = $options[$columnPassword];
        unset($options[$columnPassword]);
        $object = QueryBuilder::table($table)->where($options)->first();
        if(!Hash::check($paramPassword, $object->password)) {
            return false;
        }
        return $model::where($options)->firstOrFail();
    }
}