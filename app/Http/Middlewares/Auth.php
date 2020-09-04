<?php

namespace App\Http\Middlewares;

use Closure;
use Midun\Http\Exceptions\AppException;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Midun\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Midun\Http\Exceptions\AppException
     */
    public function handle($request, Closure $next)
    {
        // throw new AppException("Not pass");
        return $next($request);
    }
}
