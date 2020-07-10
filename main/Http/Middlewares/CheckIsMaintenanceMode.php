<?php

namespace Main\Http\Middlewares;

use Closure;
use Main\Http\Exceptions\AppException;

class CheckIsMaintenanceMode
{
    /**
     * The application implementation.
     *
     * @var \Main\Container
     */
    protected $app;

    /**
     * Create a new middleware instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->app = app();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws Main\Http\Exceptions\AppException
     */
    public function handle($request, Closure $next)
    {
        if ($this->app->isDownForMaintenance()) {
            throw new AppException("This application is down for maintenance");
        }

        return $next($request);
    }
}
