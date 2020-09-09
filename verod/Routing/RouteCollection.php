<?php

namespace Midun\Routing;

class RouteCollection
{
    /**
     * Method of routing
     * 
     * @var string
     */
    private $methods;

    /**
     * Uri of routing
     * 
     * @var string
     */
    private $uri;

    /**
     * Action
     * 
     * @var mixed
     */
    private $action;

    /**
     * Name
     * 
     * @var string
     */
    private $name;

    /**
     * List of middlewares
     * 
     * @var array
     */
    private $middlewares = [];

    /**
     * Prefix
     * 
     * @var array
     */
    private $prefix = [];

    /**
     * Namespace
     * 
     * @var array
     */
    private $namespaces = [];

    /**
     * Initial constructor
     * 
     * @param string $methods
     * @param string $uri
     * @param string $name
     * @param mixed $action
     * @param array $middlewares
     * @param array $prefix
     * @param array $namespaces
     * 
     */
    public function __construct(
        $methods,
        $uri,
        $name,
        $action,
        $middlewares,
        $prefix,
        $namespaces
    ) {
        $this->methods = $methods;
        $this->uri = $uri;
        $this->name = $name;
        $this->action = $action;
        $this->middlewares = $middlewares;
        $this->prefix = is_array($prefix) ? $prefix : is_string($prefix) ? [$prefix] : null;
        $this->namespaces = is_array($namespaces) ? $namespaces : is_string($namespaces) ? [$namespaces] : null;
    }

    /**
     * Set middleware
     * 
     * @param string|array $middleware
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
     * Set namespace
     * 
     * @param string|array $namespace
     * 
     * @return self
     */
    public function namespace($namespace)
    {
        $this->namespaces[] = $namespace;
        return $this;
    }

    /**
     * Set name
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
     * Set prefix
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
     * Get uri
     * 
     * @return string
     */
    public function getUri()
    {
        return empty($this->uri)
            || !empty($this->uri)
            && $this->uri[0]
            != Routing::ROUTING_SEPARATOR
            ? Routing::ROUTING_SEPARATOR . $this->uri
            : $this->uri;
    }

    /**
     * Get method
     * 
     * @return string
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Get name 
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get action
     * 
     * @return string|array
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Get middleware
     * 
     * @return array
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * Get prefix
     * 
     * @return array
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Get namespace
     * 
     * @return array
     */
    public function getNamespace()
    {
        return $this->namespaces;
    }
}
