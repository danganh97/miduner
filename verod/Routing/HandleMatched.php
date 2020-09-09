<?php

namespace Midun\Routing;

use Midun\HandleRoute;

class HandleMatched
{
    /**
     * Flag is matched routing 
     * 
     * @var bool
     */
    var $isMatched = false;

    /**
     * Constructor of HandleMatched
     * 
     * @param string $routeParams
     * @param string $requestParams
     */
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
