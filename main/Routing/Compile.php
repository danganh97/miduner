<?php

namespace Main\Routing;

use App\Http\Kernel;
use Main\Container;
use Main\Http\Exceptions\AppException;
use Main\Pipeline\Pipeline;

class Compile
{
    const DEFAULT_SPECIFIC = '@';
    const DEFAULT_CONTROLLER_NAMESPACE = 'App\\Http\\Controllers';
    /**
     * Initial constructor
     * @param string/array $action
     * @param array $params
     * @param string $middlewares
     */
    public function __construct($action = null, array $params = null, $middlewares = null)
    {
        if ($middlewares != null) {
            return (new Pipeline(Container::getInstance()))
                ->send(app('request'))
                ->through($middlewares)
                ->then(function () use ($action, $params) {
                    return $this->handle($action, $params);
                });
        }
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
}
