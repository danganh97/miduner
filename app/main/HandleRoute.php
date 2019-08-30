<?php

namespace App\Main;

class HandleRoute
{
    private $routes;
    private $base;
    private $flag = false;

    public function __construct($routes)
    {
        $this->routes = $routes;
        return $this->run();
        $this->base = '/public';
    }

    public function __destruct()
    {
        return $this->flag == false ? (new Controller)->singleRender('404') : true;
    }

    private function run()
    {
        $requestUrl = $this->getRequestURL();
        $requestMethod = $this->getRequestMethod();
        $routes = $this->routes;
        $requestParams = explode('/', $requestUrl);

        foreach ($routes as $route) {
            $routeParams = explode('/', $route['uri']);
            if(strpos(strtolower($route['method']), strtolower($requestMethod)) !== false) {
                if (count($requestParams) === count($routeParams)) {
                    $this->compare($route['uri'], $requestUrl, $route['action']);
                }
            }
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
        } elseif (is_callable($action)) {
            return call_user_func_array($action, $params);
        } else {
            $action = isset($action[1]) ? $action[1] : $action;
            $action = is_array($action) && count($action) == 1 ? $action[0] : $action;
            throw new AppException("The $action doesn't exists !");
        }
    }

    private function compileRoute($action, array $params)
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
                throw new AppException("Controller wrong format !");
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
                return call_user_func_array([$object, $methodName], $params);
            }
            if ($cloud === false) {
                return $object($params);
            }
            throw new AppException("Method {$className}@{$methodName} doesn't exists !");
        }
        throw new AppException("Class {$className} doesn't exists !");
    }

    public function callableAction($action, array $params = null)
    {
        return $this->compileRoute($action, (array) $params);
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
}