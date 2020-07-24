<?php

namespace Main\Routing;

use Main\Http\Exceptions\AppException;

class NextPasses
{
    public function __construct($routeParams, $requestParams, $action, $middleware)
    {
        $params = [];
        foreach ($routeParams as $key => $value) {
            if (preg_match('/^{\w+}$/', $value)) {
                $params[] = $requestParams[$key];
            }
        }
        if (is_callable($action) && is_array($action) || is_string($action)) {
            $middleware = is_array($middleware) ? $middleware : [$middleware];
            return new Compile($action, $params, $middleware);
        } elseif (is_callable($action)) {
            return call_user_func_array($action, $params);
        } else {
            $action = isset($action[1]) ? $action[1] : $action;
            $action = is_array($action) && count($action) == 1 ? $action[0] : $action;
            throw new AppException("The $action doesn't exists !");
        }
    }
}