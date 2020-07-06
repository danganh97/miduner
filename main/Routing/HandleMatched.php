<?php

namespace Main\Routing;

use Main\HandleRoute;

class HandleMatched
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
            app()->singleton('routeFlag', true);
            return new NextPasses($routeParams, $requestParams, $action, $middleware);
        }
    }
}
