<?php

namespace Midun\Routing;

class Routing
{
    /**
     * Routing separator
     * 
     * @var string
     */
    const ROUTING_SEPARATOR = "/";

    /**
     * List of routes
     * 
     * @var array
     */
    private $routes = [];
    /**
     * Constructor of the Routing
     * 
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * Finding matching route
     * 
     * @return void
     */
    public function find()
    {
        $routes = $this->routes;
        $requestUrl = $this->getRequestURL();
        $requestMethod = $this->getRequestMethod();
        $requestParams = explode(Routing::ROUTING_SEPARATOR, $requestUrl);
        foreach ($routes as $route) {
            $uri = $route->getUri();
            $method = $route->getMethods();
            $prefix = $route->getPrefix();

            if (!empty($prefix)) {
                $uri = Routing::ROUTING_SEPARATOR . implode(Routing::ROUTING_SEPARATOR, $prefix) . $uri;
            }

            $routeParams = explode(Routing::ROUTING_SEPARATOR, $uri);
            if (strpos(strtolower($method), strtolower($requestMethod)) !== false) {
                if (count($requestParams) === count($routeParams)) {
                    $checking = new HandleMatched($uri, $requestUrl);
                    if ($checking->isMatched === true) {
                        return new NextPasses($routeParams, $requestParams, $route);
                    }
                }
            }
        }
        return $this->handleNotFound();
    }

    /**
     * Handle not found
     * 
     * @return void
     */
    private function handleNotFound()
    {
        return app()->make('view')->render('404');
    }

    /**
     * Get request url
     * @return string
     */
    private function getRequestURL()
    {
        $uri = urldecode(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );
        return $uri === '' || empty($uri) ? Routing::ROUTING_SEPARATOR : $uri;
    }

    /**
     * Get request method
     * @return string
     */
    private function getRequestMethod()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "GET";
    }
}
