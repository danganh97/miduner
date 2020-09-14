<?php

namespace App\Http;

use Midun\Http\Kernel as MidunerKernel;

class Kernel extends MidunerKernel
{
    public array $routeMiddlewares = [
        'web:api' => \App\Http\Middlewares\ApiAuth::class,
        'web:auth' => \App\Http\Middlewares\Auth::class,
        'checkPermission' => \App\Http\Middlewares\CheckPermission::class,
    ];

        /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected array $middlewares = [
        \Midun\Http\Middlewares\CheckIsMaintenanceMode::class,
        \Midun\Http\Middlewares\ValidatePostSize::class,
        \Midun\Http\Middlewares\LimitRequest::class
    ];

}