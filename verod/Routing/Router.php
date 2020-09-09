<?php

namespace Midun\Routing;

class Router
{
    /**
     * List of middleware
     * 
     * @var array
     */
    private $middlewares = [];

    /**
     * Prefix of routes
     * 
     * @var string
     */
    private $prefix;

    /**
     * Name of route
     * 
     * @var string
     */
    private $name;

    /**
     * Namespace of route
     * 
     * @var string
     */
    private $namespace;

    /**
     * Except method resource
     * 
     * @var array
     */
    private $except;

    /**
     * List of resources
     * 
     * @var array
     */
    private $resources = [];

    /**
     * List of routes
     * 
     * @var array
     */
    private $routes = [];

    /**
     * Get method
     * 
     * @param string $uri
     * @param string $method
     * 
     * @return \Midun\Routing\RouteCollection
     */
    public function get($uri, $action)
    {
        return $this->addRoute('GET', $uri, $action);
    }

    /**
     * Post method
     * 
     * @param string $uri
     * @param string $method
     * 
     * @return \Midun\Routing\RouteCollection
     */
    public function post($uri, $action)
    {
        return $this->addRoute('POST', $uri, $action);
    }

    /**
     * Put method
     * 
     * @param string $uri
     * @param string $method
     * 
     * @return \Midun\Routing\RouteCollection
     */
    public function put($uri, $action)
    {
        return $this->addRoute('PUT', $uri, $action);
    }
    /**
     * Patch method
     * 
     * @param string $uri
     * @param string $method
     * 
     * @return \Midun\Routing\RouteCollection
     */
    public function patch($uri, $action)
    {
        return $this->addRoute('PATCH', $uri, $action);
    }
    /**
     * Any method
     * 
     * @param string $uri
     * @param string $method
     * 
     * @return \Midun\Routing\RouteCollection
     */
    public function any($uri, $action)
    {
        return $this->addRoute('GET|POST', $uri, $action);
    }
    /**
     * Delete method
     * 
     * @param string $uri
     * @param string $method
     * 
     * @return \Midun\Routing\RouteCollection
     */
    public function delete($uri, $action)
    {
        return $this->addRoute('DELETE', $uri, $action);
    }

    /**
     * Add routing
     * 
     * @param string $methods
     * @param string $uri
     * @param string $action
     * 
     * @return \Midun\Routing\RouteCollection
     */
    private function addRoute($methods, $uri, $action)
    {
        $middlewares = $this->middlewares;
        $prefix = $this->prefix;
        $namespace = $this->namespace;
        $name = $this->name;

        $router = new RouteCollection($methods, $uri, $name, $action, $middlewares, $prefix, $namespace);
        $this->routes[] = $router;
        return $router;
    }

    /**
     * Add middleware
     * 
     * @param mixed $middleware
     * 
     * @return self
     */
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
     * Register route
     * 
     * @return true
     */
    public function register()
    {
        $this->middlewares = [];
        $this->prefix = null;
        $this->namespace = null;
        $this->name = null;
        $this->except = null;
        $this->resources = [];
        return true;
    }

    /**
     * Add prefix
     * 
     * @param string $prefix
     * 
     * @return self
     */
    public function prefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Add namespace
     * 
     * @param string $namespace
     * 
     * @return self
     */
    public function namespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * Include route file with parameters
     * 
     * @param string $path
     */
    public function group($path)
    {
        if (file_exists($path)) {
            require $path;
            return $this;
        }
        throw new AppException("$path not found");
    }

    /**
     * Register resource
     *
     * @param string $uri
     * @param string $action
     *
     * @return $this
     */
    public function resource($uri, $action)
    {
        $resource = [
            [
                compact('uri', 'action')
            ],
            $this->name, $this->middlewares, $this->prefix, $this->namespace
        ];
        $routeResource = new RouteResource(...$resource);
        $this->routes[] = $routeResource;
        return $routeResource;
    }

    /**
     * Register many routes
     *
     * @param array $resources
     *
     * @return $this
     */
    public function resources(array $resources)
    {
        $middlewares = $this->middlewares;
        $prefix = $this->prefix;
        $namespace = $this->namespace;
        $name = $this->name;
        foreach ($resources as $key => $resource) {
            $items[] = [
                'uri' => $key,
                'action' => $resource
            ];
        }
        $resources = [$items, $name, $middlewares, $prefix, $namespace];
        $routeResource = new RouteResource(...$resources);
        $this->routes[] = $routeResource;
        return $routeResource;
    }

    /**
     * Get list routes
     * 
     * @return array
     */
    public function routes()
    {
        return $this->routes;
    }

    /**
     * Run the routing
     * 
     * @return mixed
     */
    public function run()
    {
        $routing = new Routing($this->collect());

        return $routing->find();
    }

    /**
     * Collect all routing defined
     * 
     * @return array
     */
    public function collect()
    {
        $routes = [];

        foreach ($this->routes() as $object) {
            if ($object instanceof RouteResource) {
                $routes = array_merge($routes, $object->parse());
            } else {
                $routes[] = $object;
            }
        }

        return $routes;
    }

    /**
     * Callable action to controller method
     * 
     * @param array $action
     * @param array $params = []
     * 
     * @return void
     */
    public function callableAction(array $action, array $params = [])
    {
        $rc = new RouteCollection(
            __FUNCTION__,
            __FUNCTION__,
            __FUNCTION__,
            $action,
            __FUNCTION__,
            __FUNCTION__,
            NULL
        );

        $compile = new Compile($rc, $params);

        return $compile->handle();
    }
}
