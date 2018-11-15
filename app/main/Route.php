<?php

use App\Main\Controller;

class Route
{
    private static $routes = [];
    private $base;
    private $flag = false;

    public function __construct()
    {
        $this->base = config('app.baseurl');
    }

    public function __destruct()
    {
        return $this->flag == false ? (new Controller)->singleRender('404') : true;
    }

    private function getRequestURL()
    {
        $uri = explode('?', $_SERVER['REQUEST_URI'], 2);
        $url = isset($_SERVER['REQUEST_URI']) ? $uri[0] : '/';
        $url = str_replace($this->base, '', $url);
        return $url === '' || empty($url) ? '/' : $url;
    }

    private function getRequestMethod()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "GET";
    }

    private static function addRoute($method, $url, $action)
    {
        self::$routes[] = [$method, $url, $action];
    }

    public static function get($url, $action)
    {
        self::addRoute('GET', $url, $action);
    }

    public static function post($url, $action)
    {
        self::addRoute('POST', $url, $action);
    }

    public static function put($url, $action)
    {
        self::addRoute('POST', $url, $action);
    }

    public static function patch($url, $action)
    {
        self::addRoute('POST', $url, $action);
    }

    public static function delete($url, $action)
    {
        self::addRoute('POST', $url, $action);
    }

    public static function any($url, $action)
    {
        self::addRoute('GET|POST', $url, $action);
    }

    public static function resource($url, $action)
    {
        self::get($url, $action . '@' . 'index');
        self::get($url . '/create', $action . '@' . 'create');
        self::post($url, $action . '@' . 'store');
        self::get($url . '/{id}/show', $action . '@' . 'show');
        self::get($url . '/{id}/edit', $action . '@' . 'edit');
        self::put($url . '/{id}/update', $action . '@' . 'update');
        self::delete($url . '/{id}/delete', $action . '@' . 'destroy');
    }

    public function handle($routeParams, $requestParams, $action)
    {
        $pazeROUTE = explode('/', $routeParams);
        $pazeREQUEST = explode('/', $requestParams);
        $params = [];
        foreach ($pazeROUTE as $key => $value) {
            if (preg_match('/^{\w+}$/', $value)) {
                $params[] = $pazeREQUEST[$key];
            }
        }

        if (is_callable($action) && is_array($action) || is_string($action)) {
            return $this->compileRoute($action, $params);
        }elseif(is_callable($action)){
            return call_user_func_array($action, $params);
        }else{
            throw new \App\Main\AppException("The $action doesn't exists !");
        }
    }

    public function compare($routeParams, $requestParams, $action)
    {
        $changeROUTE = preg_replace('/\{\w+\}/', '*', $routeParams);
        $pazeREQUEST = explode('/', $requestParams);
        $pazeROUTE = explode('/', $changeROUTE);
        foreach ($pazeROUTE as $key => $value) {
            if ($value == '*') {
                $pazeREQUEST[$key] = '*';
            }
        }
        if ($pazeREQUEST === $pazeROUTE) {
            $this->flag = true;
            return $this->handle($routeParams, $requestParams, $action);
        }
    }

    public function map()
    {
        $requestUrl = $this->getRequestURL();
        $requestMethod = $this->getRequestMethod();
        $routes = self::$routes;

        $requestParams = explode('/', $requestUrl);

        foreach ($routes as $route) {
            $listMethod[] = $route[0];
            $listUri[] = $route[1];
            $listAction[] = $route[2];
        }

        foreach ($listUri as $keyURI => $route) {
            $routeParams = explode('/', $route);

            if (count($requestParams) === count($routeParams) && strpos(strtolower($listMethod[$keyURI]), strtolower($requestMethod)) !== false) {
                $this->compare($route, $requestUrl, $listAction[$keyURI]);
            }
        }
    }

    private function compileRoute($action, $params)
    {
        if (!is_array($action)) {
            $action = explode('@', $action);
        }
        switch (count($action)) {
            case 2:
                $className = $action[0];
                $methodName = $action[1];
                $cloud = true;
                break;
            case 1:
                $className = $action[0];
                $methodName = null;
                $cloud = false;
                break;
            default:
                throw new App\Main\AppException("Controller wrong format !");
                break;
        }

        if (explode('\\', $className)[0] === 'App') {
            $controller = $className;
        } else {
            $controller = 'App\\Controllers\\' . $className;
        }
        if (class_exists($controller)) {
            $object = new $controller;
            if (method_exists($controller, $methodName) && $cloud === true) {
                call_user_func_array([$object, $methodName], $params);return;
            }
            if ($cloud === false) {
                $object($params);return;
            }
            throw new App\Main\AppException("Method {$className}@{$methodName} doesn't exists !");
        }
        throw new App\Main\AppException("Class {$className} doesn't exists !");
    }

    public function callableAction($action, array $params)
    {
        return $this->compileRoute($action, $params);
    }

    public function run()
    {
        $this->map();
    }
}
