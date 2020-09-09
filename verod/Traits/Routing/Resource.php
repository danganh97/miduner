<?php

namespace Midun\Traits\Routing;

use Midun\Routing\RouteCollection;

trait Resource
{
    /**
     * Make route index
     * 
     * @param array $resource
     * 
     * @return \Midun\Routing\RouteCollection
     */
    public function makeIndex(array $resource)
    {
        if (!in_array('index', $this->except)) {
            return $this->createRoute(
                'GET',
                "/{$resource['uri']}",
                $this->name . "{$resource['uri']}.index",
                "{$resource['action']}@index",
                $this->middlewares,
                $this->prefix,
                $this->namespaces
            );
        }
    }

    /**
     * Make route create
     * 
     * @param array $resource
     * 
     * @return \Midun\Routing\RouteCollection
     */
    public function makeCreate(array $resource)
    {
        if (!in_array('create', $this->except)) {
            return $this->createRoute(
                'GET',
                "/{$resource['uri']}/create",
                $this->name . "{$resource['uri']}.create",
                "{$resource['action']}@create",
                $this->middlewares,
                $this->prefix,
                $this->namespaces
            );
        }
    }

    /**
     * Make route shows
     * 
     * @param array $resource
     * 
     * @return \Midun\Routing\RouteCollection
     */
    public function makeShow(array $resource)
    {
        if (!in_array('show', $this->except)) {
            return $this->createRoute(
                'GET',
                "/{$resource['uri']}" . '/' . '{' . $resource['uri'] . '}',
                $this->name . "{$resource['uri']}.show",
                "{$resource['action']}@show",
                $this->middlewares,
                $this->prefix,
                $this->namespaces
            );
        }
    }

    /**
     * Make route store
     * 
     * @param array $resource
     * 
     * @return \Midun\Routing\RouteCollection
     */
    public function makeStore(array $resource)
    {
        if (!in_array('store', $this->except)) {
            return $this->createRoute(
                'POST',
                "/{$resource['uri']}",
                $this->name . "{$resource['uri']}.store",
                "{$resource['action']}@store",
                $this->middlewares,
                $this->prefix,
                $this->namespaces
            );
        }
    }

    /**
     * Make route edit
     * 
     * @param array $resource
     * 
     * @return \Midun\Routing\RouteCollection
     */
    public function makeEdit(array $resource)
    {
        if (!in_array('edit', $this->except)) {
            return $this->createRoute(
                'GET',
                "/{$resource['uri']}/{{$resource['uri']}}/edit",
                $this->name . "{$resource['uri']}.edit",
                "{$resource['action']}@edit",
                $this->middlewares,
                $this->prefix,
                $this->namespaces
            );
        }
    }

    /**
     * Make route update
     * 
     * @param array $resource
     * 
     * @return \Midun\Routing\RouteCollection
     */
    public function makeUpdate(array $resource)
    {
        if (!in_array('update', $this->except)) {
            return $this->createRoute(
                'PUT',
                "/{$resource['uri']}/{{$resource['uri']}}",
                $this->name . "{$resource['uri']}.update",
                "{$resource['action']}@update",
                $this->middlewares,
                $this->prefix,
                $this->namespaces
            );
        }
    }

    /**
     * Make route delete
     * 
     * @param array $resource
     * 
     * @return \Midun\Routing\RouteCollection
     */
    public function makeDelete(array $resource)
    {
        if (!in_array('destroy', $this->except)) {
            return $this->createRoute(
                'DELETE',
                "/{$resource['uri']}/{{$resource['uri']}}",
                $this->name . "{$resource['uri']}.destroy",
                "{$resource['action']}@destroy",
                $this->middlewares,
                $this->prefix,
                $this->namespaces
            );
        }
    }

    /**
     * Create RouteCollection
     * 
     * @param string $method
     * @param string $uri
     * @param string $name
     * @param array $middlewares
     * @param mixed $action
     * @param array $prefix
     * @param array $namespace
     * 
     * @return \Midun\Routing\RouteCollection
     */
    public function createRoute($methods, $uri, $name, $action, $middlewares, $prefix, $namespace)
    {
        return new RouteCollection($methods, $uri, $name, $action, $middlewares, $prefix, $namespace);
    }
}
