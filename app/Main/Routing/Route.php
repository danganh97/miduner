<?php

namespace App\Main\Routing;

class Route
{
    public function __construct(array $routes = [])
    {
        if (count($routes) > 0) {
            $requestUrl = $this->getRequestURL();
            $requestMethod = $this->getRequestMethod();
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
        $uri = urldecode(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );
        return $uri === '' || empty($uri) ? '/' : $uri;
    }

    private function getRequestMethod()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "GET";
    }
}
