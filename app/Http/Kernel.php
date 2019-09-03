<?php

namespace App\Http;

class Kernel
{
    public $routeMiddleware = [
        'auth' => \App\Http\Middlewares\Auth::class
    ];
}