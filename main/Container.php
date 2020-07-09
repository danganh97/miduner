<?php

namespace Main;

use Closure;
use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use ReflectionParameter;
use Main\Traits\Instance;
use Main\Http\Exceptions\AppException;

class Container
{
    use Instance;
    /**
     * Storage saving registry variables
     * @var array $storage
     */
    private $storage = [];

    /**
     * Storage saving bindings objects
     * @var array $bindings
     */
    private $bindings = [];

    /**
     * List of resolved bindings
     */
    private $resolved = [];

    /**
     *
     * Make a entity
     * @param string $entity
     * @return mixed
     */
    public function resolve($entity)
    {
        $object = $this->build($entity);
        $this->resolved[$entity] = $object;
        return $object;
    }

    /**
     *
     * Make a entity
     * @param string $entity
     * @return mixed
     */
    public function make($entity)
    {
        return $this->resolve($entity);
    }

    /**
     * Register a concrete to abstract
     * @param string $abstract
     * @param mixed $concrete
     * @return void
     */
    public function singleton($abstract, $concrete)
    {
        $this->bind($abstract, $concrete);
    }

    /**
     * Binding abstract to classes
     * @param string $abstract
     * @param string $concrete
     *
     * @return void
     */
    public function bind($abstract, $concrete = null)
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }
        if (!$concrete instanceof Closure) {
            $concrete = $this->getClosure($concrete);
        }
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Get the Closure to be used when building a type.
     *
     * @param  string  $abstract
     * @param  string  $concrete
     * @return \Closure
     */
    private function getClosure($concrete)
    {
        return function () use ($concrete) {
            return $this->build($concrete);
        };
    }

    /**
     * Instantiate a concrete instance of the given type.
     *
     * @param  string  $concrete
     * @return mixed
     *
     * @throws AppException
     */
    public function build($concrete)
    {
        if (is_string($concrete) && $this->resolved($concrete)) {
            return $this->resolved[$concrete];
        }

        if ($concrete instanceof Closure) {
            return $concrete();
        }

        if (isset($this->bindings[$concrete])) {
            return $this->build($this->bindings[$concrete]);
        }
        
        $reflector = new ReflectionClass($concrete);
        if (!$reflector->isInstantiable()) {
            throw new AppException("Class {$concrete} is not an instantiable !");
        }
        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return new $concrete;
        }
        $dependencies = $constructor->getParameters();
        
        $instances = $this->resolveDependencies($dependencies);

        return $reflector->newInstanceArgs($instances);
    }

    /**
     * Check is resolved
     * @param string $concrete
     *
     * @return boolean
     */
    private function resolved(string $abstract)
    {
        return isset($this->resolved[$abstract]);
    }

    /**
     * Resolve all of the dependencies from the ReflectionParameters.
     *
     * @param  array  $dependencies
     * @return array
     */
    private function resolveDependencies(array $dependencies)
    {
        $array = [];
        foreach ($dependencies as $dependency) {
            if (is_object($dependency->getClass())) {
                $object = $dependency->getClass()->getName();
                $array[$dependency->getName()] = $this->make($object);
            }
        }

        return $array;
    }

    /**
     * Resolve list of dependencies from options
     * @param string $controller
     * @param string $methodName
     * @param array $params
     *
     * @return array
     */
    public function resolveDependencyWithOptions($controller, $methodName, $params)
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
            $object = app()->build($object);
            if ($object instanceof FormRequest) {
                $object->executeValidate();
            }
            return $object;
        } catch (ArgumentCountError $e) {
            throw new AppException($e->getMessage());
        }
    }

    /**
     * Get list of bindings
     *
     * @return array
     */
    public function getBindings()
    {
        return $this->bindings;
    }
}
