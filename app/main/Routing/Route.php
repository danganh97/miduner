<?php

namespace App\Main\Routing;

class Route
{
    private $base;

    public function __construct(array $routes = [])
    {
        if (count($routes) > 0) {
            $requestUrl = $this->getRequestURL();
            $requestMethod = $this->getRequestMethod();
            $this->base = '/public';
            $requestParams = explode('/', $requestUrl);
            foreach ($routes as $route) {
                $routeParams = explode('/', $route['uri']);
                if (strpos(strtolower($route['method']), strtolower($requestMethod)) !== false) {
                    if (count($requestParams) === count($routeParams)) {
                        new Compare($route['uri'], $requestUrl, $route['action'], $route['middleware']);
                    }
                }
            }
        }
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
