<?php

namespace Main\Routing;

use App\Http\Kernel;
use Main\Http\Exceptions\AppException;

class Compile
{
    const DEFAULT_SPECIFIC = '@';
    const DEFAULT_CONTROLLER_NAMESPACE = 'App\\Http\\Controllers';
    /**
     * Initial constructor
     * @param string/array $action
     * @param array $params
     * @param string $middleware
     */
    public function __construct($action = null, array $params = null, $middleware = null)
    {
        if ($middleware != null) {
            return $this->middlewaresHandler($middleware, $action, $params);
        }
        return $this->handle($action, $params);
    }

    /**
     * Handle route action
     * @param string/array $action
     * @param array $params
     *
     * @return void
     */
    public function handle($action, $params)
    {
        if (!is_array($action)) {
            $action = explode(self::DEFAULT_SPECIFIC, $action);
        }
        switch (count($action)) {
            case 2:
                list($controller, $methodName) = $action;
                break;
            case 1:
                list($controller) = $action;
                $methodName = '__invoke';
                break;
            default:
                throw new AppException("Controller wrong format !");
                break;
        }
        if (strpos($controller, self::DEFAULT_CONTROLLER_NAMESPACE) === false) {
            $controller = 'App\\Http\\Controllers\\' . $controller;
        }
        if (class_exists($controller)) {
            $object = app()->build($controller);
            $params = app()->resolveDependencyWithOptions($controller, $methodName, $params);
            if (method_exists($controller, $methodName)) {
                return call_user_func_array([$object, $methodName], $params);
            }
            throw new AppException("Method {$controller}@{$methodName} doesn't exists !");
        }
        throw new AppException("Class {$controller} doesn't exists !");
    }

    /**
     * Handle middleware callback
     * @param string $middleware
     * @param array/string $action
     * @param array $param
     * @return Main\Http\Middleware
     *
     * @throws AppException
     */
    public function middlewaresHandler($middleware, $action, $params)
    {
        if (count(explode('\\', $middleware)) > 1) {
            new $middleware($this, $action, $params);
        } else {
            $kernelMiddlewares = (new Kernel)->routeMiddlewares;
            if (isset($kernelMiddlewares[$middleware])) {
                return new $$kernelMiddlewares[$middleware]($this, $action, $params);
            }
            throw new AppException("Middleware {$middleware} doesn't exists");
        }
    }
}
