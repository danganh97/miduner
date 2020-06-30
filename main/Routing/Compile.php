<?php

namespace Main\Routing;

use App\Http\Exceptions\Exception;
use App\Http\Kernel;
use Main\Http\FormRequest;
use Main\Http\RequestValidation;
use Main\Http\Request;

class Compile
{
    private $requestInstance;

    public function __construct($action = null, array $params = null, $middleware = null)
    {
        if ($middleware != null) {
            return $this->handleMiddleware($middleware, $action, $params);
        }
        return $this->handle($action, $params);
    }

    public function handle($action, $params)
    {
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
            $params = $this->execRequest($controller, $methodName, $params);
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

    public function handleMiddleware($middleware, $action, $params)
    {
        if (count(explode('\\', $middleware)) > 1) {
            new $middleware($this, $action, $params);
        } else {
            foreach ((new Kernel)->routeMiddlewares as $key => $value) {
                if ($middleware == $key) {
                    return new $value($this, $action, $params);
                }
            }
            throw new Exception("Middleware {$middleware} doesn't exists");
        }
    }

    private function execRequest($controller, $methodName, $params)
    {
        $ref = new \ReflectionMethod($controller, $methodName);
        $listParameters = $ref->getParameters();
        $array = [];
        foreach($listParameters as $key => $param) {
            $refParam = new \ReflectionParameter([$controller, $methodName], $key);
            if(is_object($refParam->getClass())) {
                $object = $refParam->getClass()->getName();
                $array[$param->getName()] = $this->_executeValidation($object);
            } else {
                array_push($array, array_shift($params));
            }
        }
        return $array;
    }

    private function _executeValidation($object)
    {
        $object = new $object;
        if($object instanceof FormRequest) {
            $object->executeValidate();
        }
        return $object;
    }
}
