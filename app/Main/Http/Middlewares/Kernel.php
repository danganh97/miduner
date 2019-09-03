<?php

namespace App\Main\Http\Middlewares;

class Kernel
{
    public $routeMiddleware = [
        'auth' => \App\Http\Middlewares\Auth::class
    ];
}