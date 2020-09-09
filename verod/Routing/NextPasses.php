<?php

namespace Midun\Routing;

use Midun\Container;
use Midun\Pipeline\Pipeline;

class NextPasses
{
    /**
     * Constructor of NextPasses
     * 
     * @param array $routeParams
     * @param array $requestParams
     * @param mixed $action
     * @param array/string $middleware
     */
    public function __construct($routeParams, $requestParams, $route)
    {
        $params = [];

        foreach ($routeParams as $key => $value) {
            if (preg_match('/^{\w+}$/', $value)) {
                $params[] = $requestParams[$key];
            }
        }

        $action = $route->getAction();

        $middlewares = $route->getMiddlewares();

        switch (true) {
            case is_array($action) || is_string($action):
                $next = function () use ($route, $params) {
                    $compile = new Compile($route, $params);
                    return $compile->handle();
                };
                break;
            case $action instanceof \Closure:
                $next = $action;
                break;
            default:
                throw new RouteException("Action not implemented");
        }
        $httpKernel = new \App\Http\Kernel(Container::getInstance());

        foreach ($middlewares as $middleware) {
            if (!isset($httpKernel->routeMiddlewares[$middleware])) {
                throw new RouteException("Middleware '{$middleware}' not found.");
            }
        }
        return (new Pipeline(Container::getInstance()))
            ->send(app('request'))
            ->through($middlewares)
            ->then(function () use ($params, $next) {
                return $next(...$params);
            });
    }
}
