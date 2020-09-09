<?php

namespace Midun\Eloquent;

final class ModelBindingObject
{
    /**
     * Flag checking binding one resource
     */
    private $oneOf = false;

    /**
     * Flag checking binding list resource
     */
    private $listOf = false;

    /**
     * List instance object model binding
     */
    private $resources;

    /**
     * Model binding
     */
    private $model = null;

    /**
     * List of args
     */
    private $args = [];

    /**
     * Flag checking is throwable
     */
    private $isThrow = false;

    /**
     * Initial constructor
     */
    public function __construct($resources)
    {
        $this->resources = $resources;
    }

    /**
     * Receive parameters
     *
     * @param mixed list of parameters
     */
    public function receive()
    {
        list($oneOf, $listOf, $model, $args, $isThrow) = func_get_args();
        $this->oneOf = $oneOf;
        $this->listOf = $listOf;
        $this->model = $model;
        $this->args = $args;
        $this->isThrow = $isThrow;
        return $this->checkEmpty();
    }

    /**
     * Checking empty resources
     */
    private function checkEmpty()
    {
        if (empty($this->resources)) {
            if ($this->oneOf && !$this->listOf && $this->isThrow) {
                throw new EloquentException("Resource not found", 404);
            }
            return null;
        }
        if ($this->oneOf && !$this->listOf) {
            $this->resources = array_shift($this->resources);
        }
        return $this->handle();
    }

    /**
     * Execute condition and directional
     */
    private function handle()
    {
        if ($this->oneOf) {
            return $this->bindOne($this->resources);
        }
        if ($this->listOf) {
            return $this->bindMultiple($this->resources);
        }
    }

    /**
     * Binding one resource object
     *
     * @param Model $object
     *
     * @return Model
     */
    private function bindOne(Model $object)
    {
        if (isset($this->args['with']) && !empty($this->args['with'])) {
            foreach ($this->args['with'] as $with) {
                if (method_exists($object, $with)) {
                    $object->$with = $object->$with();
                } else {
                    throw new EloquentException("Method '{$with}' not found in class {$this->model}");
                }
            }
        }
        $object->callServiceHidden();
        return $object;
    }

    /**
     * Binding multiple resource objects
     *
     * @param array $resources
     *
     * @return Collection
     */
    private function bindMultiple(array $resources)
    {
        foreach ($resources as $resource) {
            $resource->callServiceHidden();
            if (!empty($this->args['with'])) {
                foreach ($this->args['with'] as $with) {
                    if (method_exists($resource, $with)) {
                        $resource->$with = $resource->$with();
                    } else {
                        throw new EloquentException("Method '{$with}' not found in class {$this->model}");
                    }
                }
            }
        }

        return new Collection($resources);
    }
}
