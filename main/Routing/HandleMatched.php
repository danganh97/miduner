<?php

namespace Main\Routing;

use Main\HandleRoute;

class HandleMatched
{
    var $isMatched = false;

    public function __construct($routeParams, $requestParams)
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
            $this->isMatched = true;
        }
    }
}
