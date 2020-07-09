<?php

namespace Main\Routing;

use App\Http\Kernel;
use ReflectionClass;
use ReflectionMethod;
use ArgumentCountError;
use ReflectionException;
use ReflectionParameter;
use Main\Http\FormRequest;
use Main\Http\Exceptions\AppException;

class Compile
{
    const DEFAULT_SPECIFIC = '@';
    /**
     * Initial constructor
     * @param string/array $action
     * @param array $params
     * @param string $middleware
     */
    public function __construct($action = null, array $params = null, $middleware = null)
    {
        if ($middleware != null) {
            return $this->handleMiddleware($middleware, $action, $params);
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
                list($className, $methodName) = $action;
                break;
            case 1:
                list($className) = $action;
                $methodName = '__invoke';
                break;
            default:
                throw new AppException("Controller wrong format !");
                break;
        }
        if (explode('\\', $className)[0] === 'App') {
            $controller = $className;
        } else {
            $controller = 'App\\Http\\Controllers\\' . $className;
        }
        if (class_exists($controller)) {
            $object = $this->initialInstance($controller);
            $params = $this->resolveDependencies($controller, $methodName, $params);
            if (method_exists($controller, $methodName)) {
                return call_user_func_array([$object, $methodName], $params);
            }
            throw new AppException("Method {$className}@{$methodName} doesn't exists !");
        }
        throw new AppException("Class {$className} doesn't exists !");
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
    public function handleMiddleware($middleware, $action, $params)
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

    /**
     * Execute request action
     * @param string $controller
     * @param string $methodName
     * @param array $params
     *
     * @return array
     */
    private function resolveDependencies($controller, $methodName, $params)
    {
        try {
            $ref = new ReflectionMethod($controller, $methodName);
            $listParameters = $ref->getParameters();
            $array = [];
            foreach ($listParameters as $key => $param) {
                $refParam = new ReflectionParameter([$controller, $methodName], $key);
                if (is_object($refParam->getClass())) {
                    $object = $refParam->getClass()->getName();
                    $array[$param->getName()] = $this->buildStacks($object);
                } else {
                    array_push($array, array_shift($params));
                }
            }
            return $array;
        } catch (ReflectionException $e) {
            throw new AppException($e->getMessage());
        }
    }

    /**
     * !! Only using in this class !!
     * Handle validation for request
     * @param string $object
     *
     * @return Object
     */
    private function buildStacks($object)
    {
        try {
            $bindings = app()->getBindings();
            if (isset($bindings[$object])) {
                $object = $bindings[$object];
            }

            $object = $this->initialInstance($object);
            if ($object instanceof FormRequest) {
                $object->executeValidate();
            }
            return $object;
        } catch (ArgumentCountError $e) {
            throw new AppException($e->getMessage());
        }
    }

    /**
     * Get object after injection dependencies
     * @param string $concrete
     *
     * @return mixed
     */
    private function initialInstance(string $concrete)
    {
        if ($concrete instanceof Closure) {
            return $concrete(app());
        }
        $reflector = new ReflectionClass($concrete);
        if (!$reflector->isInstantiable()) {
            throw new AppException("Class {$concrete} is not an instantiable !");
        }
        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return new $concrete;
        }
        $initParams = $this->resolveDependencies($concrete, '__construct', []);
        return $reflector->newInstanceArgs($initParams);
    }
}
