<?php

namespace App\Http;

use Main\Http\Kernel as MidunerKernel;

class Kernel extends MidunerKernel
{
    public $routeMiddlewares = [
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
    protected $middlewares = [
        \Main\Http\Middlewares\CheckIsMaintenanceMode::class,
        \Main\Http\Middlewares\ValidatePostSize::class,
    ];

}