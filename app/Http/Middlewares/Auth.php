<?php

namespace App\Http\Middlewares;

use App\Main\Middleware;

class Auth extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle($callback, $action, $params)
    {
        return parent::next($callback, $action, $params);
    }
}
