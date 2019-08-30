<?php

namespace App\Main;

use App\Main\Routing\Route;

class HandleRoute
{
    private $routes;
    private $base;
    public $flag = false;

    public function __construct($routes = null)
    {
        app()->routes = $routes;
        if($routes) {
            return new Route($routes);
        }
    }

    public function __destruct()
    {
        return app()->routeFlag != true ? (new Controller)->singleRender('404') : true;
    }

}