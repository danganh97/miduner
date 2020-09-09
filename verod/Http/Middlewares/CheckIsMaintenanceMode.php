<?php

namespace Midun\Http\Middlewares;

use Closure;

class CheckIsMaintenanceMode
{
    /**
     * The application implementation.
     *
     * @var \Midun\Container
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
     * @param  \Midun\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws MiddlewareException
     */
    public function handle($request, Closure $next)
    {
        if ($this->app->isDownForMaintenance()) {
            die("This application is down for maintenance");
        }

        return $next($request);
    }
}
