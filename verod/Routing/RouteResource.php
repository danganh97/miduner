<?php

namespace Midun\Routing;

use Midun\Traits\Routing\Resource;

class RouteResource
{
    use Resource;

    /**
     * List of middleware
     * 
     * @var array
     */
    private $middlewares = [];

    /**
     * Prefix of routes
     * 
     * @var array
     */
    private $prefix = [];

    /**
     * Namespace of route
     * 
     * @var array
     */
    private $namespaces = [];

    /**
     * Resources of route
     * 
     * @var array
     */
    private $resources = [];

    /**
     * Except for route
     * 
     * @var array
     */
    private $except = [];

    /**
     * Initial RouteResource
     * 
     * @param array $resource
     * @param string $name
     * @param array $middlewares
     * @param array $prefix
     * @param array $namespaces
     */
    public function __construct()
    {
        list(
            $resource,
            $name,
            $middlewares,
            $prefix,
            $namespaces
        ) = func_get_args();

        $this->middlewares = $middlewares;
        $this->prefix = $prefix;
        $this->namespaces = $namespaces;
        $this->name = $name;
        $this->resources = $resource;
    }
    /**
     * Except function
     * @param array $methods
     *
     * @return $this
     */
    public function except(array $methods)
    {
        $this->except = $methods;
        return $this;
    }

    public function middleware($middleware)
    {
        if (!is_array($middleware)) {
            array_push($this->middlewares, $middleware);
        } else {
            $this->middlewares = array_merge($this->middlewares, $middleware);
        }
        return $this;
    }

    /**
     * Register namespace
     * 
     * @param string $namespace
     * 
     * @return self
     */
    public function namespace($namespace)
    {
        $this->namespaces[] = $namespace;
        return $this;
    }

    /**
     * Register name of route
     * 
     * @param string $name
     * 
     * @return self
     */
    public function name($name)
    {
        $this->name .= $name;
        return $this;
    }

    /**
     * Register prefix
     * 
     * @param string $prefix
     * 
     * @return self
     */
    public function prefix($prefix)
    {
        $this->prefix[] = $prefix;
        return $this;
    }

    /**
     * Parse list resource to RouteCollections
     * 
     * @return array
     */
    public function parse()
    {
        $routes = [];

        foreach ($this->resources as $resource) {
            $routes[] = $this->makeIndex($resource);
            $routes[] = $this->makeCreate($resource);
            $routes[] = $this->makeShow($resource);
            $routes[] = $this->makeStore($resource);
            $routes[] = $this->makeEdit($resource);
            $routes[] = $this->makeUpdate($resource);
            $routes[] = $this->makeDelete($resource);
        }

        return array_filter($routes, function ($route) {return !is_null($route);});
    }
}
