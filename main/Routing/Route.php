<?php

namespace Main\Routing;

use Main\Routing\Compile;
use Main\Routing\RouteMiddleware;

class Route
{
    /**
     * Uri response to
     * @var string $uri
     */
    private $uri;

    /**
     * The controller instance
     * @var array/string $action
     */
    private $action;

    /**
     * Http method response
     * @var string $method
     */
    private $method;

    /**
     * Name of the route
     * @var string $name
     */
    private $name;

    /**
     * Middleware of the route
     * @var string $middleware
     */
    private $middleware;

    /**
     * Except method
     * @var array $except
     */
    private $except = [];

    /**
     * List of resources
     * @var array $resources
     */
    private static $resources = [];

    /**
     * List of routing
     * @var array $routes
     */
    private static $routes = [];

    /**
     * Initial constructor Route
     * @param string $uri
     * @param array/string $action
     * @param string $method
     */
    public function __construct($uri = null, $action = null, $method = 'GET')
    {
        $this->uri = $uri;
        $this->action = $action;
        $this->method = $method;
    }

    /**
     * Register get route
     *
     * @param string $uri
     * @param string $action
     *
     * @return $this
     */
    public static function get($uri, $action)
    {
        return new self($uri, $action, 'GET');
    }

    /**
     * Register post route
     *
     * @param string $uri
     * @param string $action
     *
     * @return $this
     */
    public static function post($uri, $action)
    {
        return new self($uri, $action, 'POST');
    }

    /**
     * Register put route
     *
     * @param string $uri
     * @param string $action
     *
     * @return $this
     */
    public static function put($uri, $action)
    {
        return new self($uri, $action, 'GET');
    }

    /**
     * Register path route
     *
     * @param string $uri
     * @param string $action
     *
     * @return $this
     */
    public static function patch($uri, $action)
    {
        return new self($uri, $action, 'GET');
    }

    /**
     * Register delete route
     *
     * @param string $uri
     * @param string $action
     *
     * @return $this
     */
    public static function delete($uri, $action)
    {
        return new self($uri, $action, 'GET');
    }

    /**
     * Register any route
     *
     * @param string $uri
     * @param string $action
     *
     * @return $this
     */
    public static function any($uri, $action)
    {
        return new self($uri, $action, 'GET|POST');
    }

    /**
     * Register resource
     *
     * @param string $uri
     * @param string $action
     *
     * @return $this
     */
    public static function resource($uri, $action)
    {
        array_push(self::$resources, [
            'uri' => $uri,
            'action' => $action,
        ]);
        return new self;
    }

    /**
     * Register many routes
     *
     * @param array $resources
     *
     * @return $this
     */
    public static function resources(array $resources)
    {
        foreach ($resources as $key => $resource) {
            array_push(self::$resources, [
                'uri' => $key,
                'action' => $resource,
            ]);
        }
        return new self;
    }

    /**
     * Name function
     * @param string $name
     *
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Middleware function
     *
     * @param string $middleware
     *
     * @return $this
     */
    public function middleware($middleware)
    {
        $this->middleware = $middleware;
        return $this;
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

    /**
     * Call new action
     * @param string $action
     * @param array $params
     *
     * @return Compile
     */
    public function callableAction($action, array $params = null)
    {
        return (new Compile($action, (array) $params));
    }

    /**
     * Handle resources route
     * @param array $resources
     *
     * @return void
     */
    private function handleResourcesRouter(array $resources)
    {
        foreach ($resources as $resource) {
            $flag['index'] = false;
            $flag['create'] = false;
            $flag['store'] = false;
            $flag['show'] = false;
            $flag['edit'] = false;
            $flag['update'] = false;
            $flag['destroy'] = false;
            $uri['index'] = "/{$resource['uri']}";
            $uri['create'] = "/{$resource['uri']}/create";
            $uri['store'] = "/{$resource['uri']}";
            $uri['show'] = '/' . $resource['uri'] . '/' . '{' . $resource['uri'] . '}';
            $uri['edit'] = '/' . $resource['uri'] . '/' . '{' . $resource['uri'] . '}' . '/' . 'edit';
            $uri['update'] = '/' . $resource['uri'] . '/' . '{' . $resource['uri'] . '}';
            $uri['destroy'] = '/' . $resource['uri'] . '/' . '{' . $resource['uri'] . '}';
            if (self::$routes != []) {
                foreach (self::$routes as $route) {
                    if ($route['uri'] == $uri['index'] && strpos(strtolower($route['method']), 'get') !== false) {
                        $flag['index'] = true;
                    }
                    if ($route['uri'] == $uri['create'] && strpos(strtolower($route['method']), 'get') !== false) {
                        $flag['create'] = true;
                    }
                    if ($route['uri'] == $uri['store'] && strpos(strtolower($route['method']), 'post') !== false) {
                        $flag['store'] = true;
                    }
                    if ($route['uri'] == $uri['show'] && strpos(strtolower($route['method']), 'get') !== false) {
                        $flag['show'] = true;
                    }
                    if ($route['uri'] == $uri['edit'] && strpos(strtolower($route['method']), 'get') !== false) {
                        $flag['edit'] = true;
                    }
                    if ($route['uri'] == $uri['update'] && strpos(strtolower($route['method']), 'put') !== false) {
                        $flag['update'] = true;
                    }
                    if ($route['uri'] == $uri['destroy'] && strpos(strtolower($route['method']), 'delete') !== false) {
                        $flag['destroy'] = true;
                    }
                }
            }
            if (!in_array('index', $this->except) && $flag['index'] == false) {
                $this->pushRouteToCollections("/{$resource['uri']}", "{$resource['action']}@index", 'GET', "{$resource['uri']}.index", $this->middleware);
            }
            if (!in_array('create', $this->except) && $flag['create'] == false) {
                $this->pushRouteToCollections("/{$resource['uri']}/create", "{$resource['action']}@create", 'GET', "{$resource['uri']}.create", $this->middleware);
            }
            if (!in_array('show', $this->except) && $flag['show'] == false) {
                $this->pushRouteToCollections("/{$resource['uri']}" . '/' . '{' . $resource['uri'] . '}', "{$resource['action']}@show", 'GET', "{$resource['uri']}.show", $this->middleware);
            }
            if (!in_array('store', $this->except) && $flag['store'] == false) {
                $this->pushRouteToCollections("/{$resource['uri']}", "{$resource['action']}@store", 'POST', "{$resource['uri']}.store", $this->middleware);
            }
            if (!in_array('edit', $this->except) && $flag['edit'] == false) {
                $this->pushRouteToCollections('/' . $resource['uri'] . '/' . '{' . $resource['uri'] . '}' . '/' . 'edit', "{$resource['action']}@edit", 'GET', "{$resource['uri']}.edit", $this->middleware);
            }
            if (!in_array('update', $this->except) && $flag['update'] == false) {
                $this->pushRouteToCollections('/' . $resource['uri'] . '/' . '{' . $resource['uri'] . '}', "{$resource['action']}@update", 'PUT', "{$resource['uri']}.update", $this->middleware);
            }
            if (!in_array('destroy', $this->except) && $flag['destroy'] == false) {
                $this->pushRouteToCollections('/' . $resource['uri'] . '/' . '{' . $resource['uri'] . '}', "{$resource['action']}@destroy", 'DELETE', "{$resource['uri']}.destroy", $this->middleware);
            }
        }
    }

    /**
     * Destruct handle
     *
     * @return void
     */
    public function __destruct()
    {
        if (self::$resources) {
            $this->handleResourcesRouter(self::$resources);
        }
        if ($this->uri != null) {
            $this->pushRouteToCollections($this->uri, $this->action, $this->method, $this->name, $this->middleware);
        }
        self::$resources = [];
    }

    /**
     * Push simple route to collection routes
     * @param  string $uri
     * @param string $action
     * @param string $method
     * @param string $name
     * @param array $middleware
     *
     * @return void
     */
    private function pushRouteToCollections($uri, $action, $method, $name, $middleware)
    {
        array_push(self::$routes, [
            'uri' => $uri,
            'action' => $action,
            'method' => $method,
            'name' => $name,
            'middleware' => $middleware ?: null,
        ]);
    }

    /**
     * Start routing
     *
     * @return Routing
     */
    public function run()
    {
        return (new Routing(self::$routes));
    }
}
