<?php

namespace Midun;

use Closure;
use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use Midun\Http\FormRequest;
use Midun\Http\Exceptions\AppException;

class Container
{
    /**
     * Version of the application
     * 
     * @var string
     */
    const VERSION = "0.1.0";

    /**
     * Instance of the application
     * @var self
     */
    private static $instance;

    /**
     * List of bindings instances
     * 
     * @var array
     */
    private $instances = [];

    /**
     * Base path of the installation
     * @var string
     */
    private $basePath;

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
     * Flag check should skip middleware
     */
    private $shouldSkipMiddleware = false;

    /**
     * Initial of container
     * 
     * @param string $basePath
     */
    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;

        $this->instance('path.route', $this->getRoutePath());
        $this->instance('path.cache', $this->getCachePath());
        $this->instance('path.config', $this->getConfigPath());
        $this->instance('path.public', $this->getPublicPath());
        $this->instance('path.storage', $this->getStoragePath());
        $this->instance('path.database', $this->getDatabasePath());

        self::$instance = $this;
    }

    /**
     * Get instance of container
     * 
     * @return self
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            return new self(...func_get_args());
        }

        return self::$instance;
    }

    /**
     * Get public path
     * 
     * @return string
     */
    private function getPublicPath()
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'public';
    }

    /**
     * Get cache path
     * 
     * @return string
     */
    private function getCachePath()
    {
        return $this->getStoragePath() . DIRECTORY_SEPARATOR . 'cache';;
    }

    /**
     * Get config path
     * 
     * @return string
     */
    private function getConfigPath()
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'config';
    }

    /**
     * Get storage path
     * 
     * @return string
     */
    private function getStoragePath()
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'storage';
    }

    /**
     * Get database path
     * 
     * @return string
     */
    private function getDatabasePath()
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'database';
    }

    /**
     * Get routing path
     * 
     * @return string
     */
    private function getRoutePath()
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'routes';
    }

    /**
     * Get base path of installation
     * 
     * @param string $path
     */
    public function basePath($path = '')
    {
        return !$path ? $this->basePath : $this->basePath . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Register instance of things
     * 
     * @param string $key
     * @param mixed $instance
     */
    private function instance(string $key, $instance)
    {
        $this->instances[$key] = $instance;
    }

    /**
     * Register instance of things
     * 
     * @param string $key
     * @param mixed $instance
     */
    private function hasInstance(string $key)
    {
        return isset($this->instances[$key]);
    }

    /**
     *
     * Make a entity
     * @param string $entity
     * @return mixed
     * 
     * @throws \Midun\Http\Exceptions\AppException
     */
    public function resolve($entity)
    {
        if (!$this->canResolve($entity)) {
            throw new AppException("Cannot resolve entity `{$entity}`.\nIt's has not binding yet.");
        }

        $object = $this->build($entity);

        if (
            $this->bound($entity)
            && $this->takeBound($entity)['shared'] === true
            && !$this->isResolved($entity)
        ) {
            $this->putToResolved($entity, $object);
        }

        return $object;
    }

    /**
     * Put to resolved
     * 
     * @param string $abstract
     * @param mixed $concrete
     * 
     * @return void
     */
    private function putToResolved(string $abstract, $concrete)
    {
        if ($this->isResolved($abstract)) {
            throw new AppException("Duplicated abstract resolve `{$abstract}`");
        }

        $this->resolved[$abstract] = $concrete;
    }

    /**
     * Check is resolved
     * 
     * @param string $abstract
     * 
     * @return bool
     */
    private function isResolved(string $abstract)
    {
        return isset($this->resolved[$abstract]);
    }

    /**
     * Check can resolve
     * 
     * @param mixed $entity
     * 
     * @return boolean
     * 
     * @throws Midun\Http\Exceptions\AppException
     */
    private function canResolve($entity)
    {
        return $this->bound($entity) || class_exists($entity) || $this->hasInstance($entity);
    }

    /**
     *
     * Make a entity
     * @param string $entity
     * @return mixed
     */
    public function make($entity)
    {
        return isset($this->instances[$entity])
            ? $this->instances[$entity]
            : $this->resolve($entity);
    }

    /**
     * Register a concrete to abstract
     * @param string $abstract
     * @param mixed $concrete
     * @return void
     */
    public function singleton($abstract, $concrete)
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * Binding abstract to classes
     * @param string $abstract
     * @param string $concrete
     * @param bool $shared
     *
     * @return void
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }
        if (!$concrete instanceof Closure) {
            $concrete = $this->getClosure($concrete);
        }
        $this->bindings[$abstract] = compact('concrete', 'shared');
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
     * @param  mixed  $concrete
     * 
     * @return mixed
     *
     * @throws AppException
     */
    public function build($concrete)
    {
        if (is_string($concrete) && $this->resolved($concrete)) {
            return $this->takeResolved($concrete);
        }

        if ($concrete instanceof Closure) {
            return call_user_func($concrete, $this);
        }
        if ($this->bound($concrete)) {
            return $this->build(
                $this->takeBound($concrete)['concrete']
            );
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

        $instances = $this->resolveConstructorDependencies($dependencies);

        return $reflector->newInstanceArgs($instances);
    }

    /**
     * Take bound dependencies
     * 
     * @param string $concrete
     * 
     * @return mixed
     */
    private function takeBound(string $concrete)
    {
        return $this->bindings[$concrete];
    }

    /**
     * Take resolved dependencies
     * 
     * @param string $concrete
     * 
     * @return mixed
     */
    private function takeResolved(string $concrete)
    {
        return $this->resolved[$concrete];
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
    private function resolveConstructorDependencies(array $dependencies)
    {
        $array = [];
        foreach ($dependencies as $dependency) {
            if ($dependency->getClass() instanceof \ReflectionClass) {
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
    public function resolveMethodDependencyWithParameters($controller, $methodName, $params)
    {
        try {
            $ref = new ReflectionMethod($controller, $methodName);
            $listParameters = $ref->getParameters();
            $array = [];
            foreach ($listParameters as $parameter) {
                if ($parameter->getClass() instanceof \ReflectionClass) {
                    $object = $parameter->getClass()->getName();
                    array_push($array, $this->buildStacks($object));
                } else {
                    if (!isset($params[$parameter->getName()])) {
                        throw new AppException("Missing parameter '{$parameter->getName()}'");
                    }
                    array_push($array, $params[$parameter->getName()]);
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
            $object = $this->build($object);
            if ($object instanceof FormRequest) {
                $object->executeValidate();
            }
            return $object;
        } catch (\ArgumentCountError $e) {
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

    /**
     * In bindings
     * 
     * @param string $entity
     * 
     * @return bool
     */
    private function bound(string $entity)
    {
        return isset($this->bindings[$entity]);
    }

    /**
     * Check is down for maintenance
     * 
     * @return bool
     */
    public function isDownForMaintenance()
    {
        return false;
    }

    /**
     * Should skip global middlewares
     * 
     * @return bool
     */
    public function shouldSkipMiddleware()
    {
        return $this->shouldSkipMiddleware;
    }

    /**
     * Get OS specific
     * 
     * @return string
     */
    public function getOS()
    {
        switch (true) {
            case stristr(PHP_OS, 'DAR'):
                return 'macosx';
            case stristr(PHP_OS, 'WIN'):
                return 'windows';
            case stristr(PHP_OS, 'LINUX'):
                return 'linux';
            default:
                return 'unknown';
        }
    }

    /**
     * Check is windows system
     * 
     * @return bool
     */
    public function isWindows()
    {
        return "windows" === $this->getOs();
    }

    /**
     * Check is windows system
     * 
     * @return bool
     */
    public function isMacos()
    {
        return "macosx" === $this->getOs();
    }

    /**
     * Check is windows system
     * 
     * @return bool
     */
    public function isLinux()
    {
        return "linux" === $this->getOs();
    }

    /**
     * Check is windows system
     * 
     * @return bool
     */
    public function unknownOs()
    {
        return "unknown" === $this->getOs();
    }
}
