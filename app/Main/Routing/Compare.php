<?php

namespace App\Main\Routing;

use App\Main\HandleRoute;

class Compare
{
    public function __construct($routeParams, $requestParams, $action, $middleware)
    {
        $changeROUTE = preg_replace('/\{\w+\}/', '*', $routeParams);
        $pazeREQUEST = explode('/', $requestParams);
        $pazeROUTE = explode('/', $changeROUTE);
        foreach ($pazeROUTE as $key => $value) {
            if ($value == '*') {
                $pazeREQUEST[$key] = '*';
            }
        }
        if ($pazeREQUEST === $pazeROUTE) {
            app()->routeFlag = true;
            return new Handle($routeParams, $requestParams, $action, $middleware);
        }
    }
}
