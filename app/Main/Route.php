<?php
namespace App\Main;

use App\Main\HandleRoute;
use App\Main\Routing\Compile;

class Route
{
    private $uri;
    private $action;
    private $method;
    private $name;
    private $middleware;
    private $except = [];
    private static $resources = [];
    private static $routes = [];

    public function __construct($uri = null, $action = null, $method = 'GET')
    {
        $this->uri = $uri;
        $this->action = $action;
        $this->method = $method;
    }

    public static function get($uri, $action)
    {
        return new self($uri, $action, 'GET');
    }

    public static function post($uri, $action)
    {
        return new self($uri, $action, 'POST');
    }

    public static function put($uri, $action)
    {
        return new self($uri, $action, 'GET');
    }

    public static function patch($uri, $action)
    {
        return new self($uri, $action, 'GET');
    }

    public static function delete($uri, $action)
    {
        return new self($uri, $action, 'GET');
    }

    public static function any($uri, $action)
    {
        return new self($uri, $action, 'GET|POST');
    }

    public static function resource($uri, $action)
    {
        array_push(self::$resources, [
            'uri' => $uri,
            'action' => $action,
        ]);
        return new self;
    }

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

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function middleware($middleware)
    {
        $this->middleware = $middleware;
        return $this;
    }

    public function callableAction($action, array $params = null)
    {
        return (new Compile($action, (array) $params));
    }

    public function __destruct()
    {
        if (self::$resources) {
            foreach (self::$resources as $resource) {
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
                        if ($route['uri'] == $uri['show'] && strpos(strtolower($route['method']), 'get') !== false) {
                            $flag['show'] = true;
                        }
                        if ($route['uri'] == $uri['store'] && strpos(strtolower($route['method']), 'post') !== false) {
                            $flag['store'] = true;
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
                    array_push(self::$routes, [
                        'uri' => "/{$resource['uri']}",
                        'action' => "{$resource['action']}@index",
                        'method' => 'GET',
                        'name' => "{$resource['uri']}.index",
                        'middleware' => $this->middleware ?: null,
                    ]);
                }
                if (!in_array('create', $this->except) && $flag['create'] == false) {
                    array_push(self::$routes, [
                        'uri' => "/{$resource['uri']}/create",
                        'action' => "{$resource['action']}@create",
                        'method' => 'GET',
                        'name' => "{$resource['uri']}.create",
                        'middleware' => $this->middleware ?: null,
                    ]);
                }
                if (!in_array('show', $this->except) && $flag['show'] == false) {
                    array_push(self::$routes, [
                        'uri' => "/{$resource['uri']}" . '/' . '{' . $resource['uri'] . '}',
                        'action' => "{$resource['action']}@show",
                        'method' => 'GET',
                        'name' => "{$resource['uri']}.show",
                        'middleware' => $this->middleware ?: null,
                    ]);
                }
                if (!in_array('store', $this->except) && $flag['store'] == false) {
                    array_push(self::$routes, [
                        'uri' => "/{$resource['uri']}",
                        'action' => "{$resource['action']}@store",
                        'method' => 'POST',
                        'name' => "{$resource['uri']}.store",
                        'middleware' => $this->middleware ?: null,
                    ]);
                }
                if (!in_array('edit', $this->except) && $flag['edit'] == false) {
                    array_push(self::$routes, [
                        'uri' => '/' . $resource['uri'] . '/' . '{' . $resource['uri'] . '}' . '/' . 'edit',
                        'action' => "{$resource['action']}@edit",
                        'method' => 'GET',
                        'name' => "{$resource['uri']}.edit",
                        'middleware' => $this->middleware ?: null,
                    ]);
                }
                if (!in_array('update', $this->except) && $flag['update'] == false) {
                    array_push(self::$routes, [
                        'uri' => '/' . $resource['uri'] . '/' . '{' . $resource['uri'] . '}',
                        'action' => "{$resource['action']}@update",
                        'method' => 'PUT',
                        'name' => "{$resource['uri']}.update",
                        'middleware' => $this->middleware ?: null,
                    ]);
                }
                if (!in_array('destroy', $this->except) && $flag['destroy'] == false) {
                    array_push(self::$routes, [
                        'uri' => '/' . $resource['uri'] . '/' . '{' . $resource['uri'] . '}',
                        'action' => "{$resource['action']}@destroy",
                        'method' => 'DELETE',
                        'name' => "{$resource['uri']}.destroy",
                        'middleware' => $this->middleware ?: null,
                    ]);
                }
            }
        }
        if ($this->uri != null) {
            array_push(self::$routes, [
                'uri' => $this->uri,
                'action' => $this->action,
                'method' => $this->method,
                'name' => $this->name,
                'middleware' => $this->middleware,
            ]);
        }
    }

    public function except(array $method)
    {
        $this->except = $method;
        return $this;
    }

    public function run()
    {
        return (new HandleRoute(self::$routes));
    }
}
