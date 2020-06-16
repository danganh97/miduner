<?php

namespace App\Main\Routing;

use App\Main\Eloquent\Controller;

class HandleRoute
{
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
        return app()->routeFlag != true ? (new Controller)->render('404') : true;
    }

}