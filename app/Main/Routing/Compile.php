<?php

namespace App\Main\Routing;

use App\Http\Exceptions\Exception;
use App\Main\Http\Middlewares\Kernel;

class Compile
{
    public function __construct($action, array $params, $middleware = null)
    {
        if($middleware != null) {
            if(count(explode('\\', $middleware)) > 1) {
                new $middleware($action);
            } else {
                foreach((new Kernel)->routeMiddleware as $key => $value) {
                    if($middleware == $key) {
                        new $value($action);
                    }
                }
            }
        }
        if (!is_array($action)) {
            $action = explode('@', $action);
        }
        switch (count($action)) {
            case 2:
                $className = $action[0];
                $methodName = $action[1];
                $cloud = true;
                break;
            case 1:
                $className = $action[0];
                $methodName = null;
                $cloud = false;
                break;
            default:
                throw new Exception("Controller wrong format !");
                break;
        }

        if (explode('\\', $className)[0] === 'App') {
            $controller = $className;
        } else {
            $controller = 'App\\Http\\Controllers\\' . $className;
        }
        if (class_exists($controller)) {
            $object = new $controller;
            if (method_exists($controller, $methodName) && $cloud === true) {
                return call_user_func_array([$object, $methodName], $params);
            }
            if ($cloud === false) {
                return $object($params);
            }
            throw new Exception("Method {$className}@{$methodName} doesn't exists !");
        }
        throw new Exception("Class {$className} doesn't exists !");
    }

}