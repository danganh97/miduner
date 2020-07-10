<?php

namespace App\Http\Middlewares;

use Closure;

class CheckPermission
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
        return $next($request);
    }
}
