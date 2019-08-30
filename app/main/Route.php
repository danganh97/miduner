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

    public static function post($url, $action)
    {
        return new self($url, $action, 'POST');
    }

    public static function put($url, $action)
    {
        return new self($url, $action, 'GET');
    }

    public static function patch($url, $action)
    {
        return new self($url, $action, 'GET');
    }

    public static function delete($url, $action)
    {
        return new self($url, $action, 'GET');
    }

    public static function any($url, $action)
    {
        return new self($url, $action, 'GET');
    }

    public static function resource($url, $action)
    {
        self::get("/$url", "$action@index");
        self::get("/$url/create", "$action@create");
        self::post("/$url", "$action@store");
        self::get("/$url/{id}/show", "$action@show");
        self::get("/$url/{id}/edit", "$action@edit");
        self::put("/$url/{id}/update", "$action@update");
        self::delete("/$url/{id}/delete", "$action@destroy");
    }

    public static function resources(array $resources)
    {
        foreach ($resources as $key => $resource) {
            self::resource($key, $resource);
        }
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
        array_push(self::$routes, [
            'uri' => $this->uri,
            'action' => $this->action,
            'method' => $this->method,
            'name' => $this->name,
            'middleware' => $this->middleware,
        ]);
    }

    public function run()
    {
        return (new HandleRoute(self::$routes));
    }
}