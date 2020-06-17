<?php

namespace App\Http;

use Main\Http\Kernel as MidunerKernel;

class Kernel extends MidunerKernel
{
    public $routeMiddlewares = [
        'auth' => \App\Http\Middlewares\Auth::class
    ];
}