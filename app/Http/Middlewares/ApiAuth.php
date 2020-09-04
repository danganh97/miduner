<?php

namespace App\Http\Middlewares;

use Closure;

class ApiAuth
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
        return $next($request);
    }
}
