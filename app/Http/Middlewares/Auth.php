<?php

namespace App\Http\Middlewares;

use Closure;
use Main\Http\Exceptions\AppException;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Main\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Main\Http\Exceptions\AppException
     */
    public function handle($request, Closure $next)
    {
        // throw new AppException("Not pass");
        return $next($request);
    }
}
