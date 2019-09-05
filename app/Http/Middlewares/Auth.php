<?php

namespace App\Http\Middlewares;

use App\Main\Middleware;

class Auth extends Middleware
{
    public function handle($callback, $action, $params)
    {
        return parent::next($callback, $action, $params);
    }
}
