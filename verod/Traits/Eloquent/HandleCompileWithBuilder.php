<?php

namespace Midun\Traits\Eloquent;

use Midun\Database\QueryBuilder\QueryException;
use Midun\Http\Exceptions\AppException;

trait HandleCompileWithBuilder
{
    /**
     * Instance of exists model
     * 
     * @var \Midun\Eloquent\Model
     */
    protected $existsModelInstance = null;

    /**
     * List of with relations
     */
    public $with;

    /**
     * Create new query builder from model
     *
     * @param ConnectionInterface $this->bindClass
     * return self
     */
    public function staticEloquentBuilder($table, $modelMeta, $method, $args = null, $instance = null)
    {
        try {
            $object = isset($modelMeta['calledClass']) ? new self($table, $modelMeta['calledClass']) : new self($table);
            switch ($method) {
                case 'find':
                case 'findOrFail':
                    try {
                        list($value) = $args;
                        return $object->$method($value, $modelMeta['primaryKey']);
                    } catch (\TypeError $e) {
                        throw new \Exception($e->getMessage());
                    }
                case 'with':
                    $object->with = $args && is_array($args[0]) ? $args[0] : $args;
                    return $object;
                case 'update':
                case 'delete':
                    $object->existsModelInstance = $instance;
                default:
                    try {
                        if (method_exists($object, $method)) {
                            return $object->$method(...$args);
                        }
                        $buildScope = $this->_getScopeMethod($method);
                        $objectModel = new $modelMeta['calledClass'];
                        if (method_exists($objectModel, $buildScope)) {
                            return $objectModel->$buildScope($object, ...$args);
                        }
                        throw new AppException("Method `{$method}` does not exist");
                    } catch (\TypeError $e) {
                        throw new \Exception($e->getMessage());
                    }
            }
        } catch (\Exception $e) {
            throw new QueryException($e->getMessage());
        }
    }

    /**
     * Handle call
     * 
     * @param string $method
     * @param array $args
     * 
     * @return \Midun\Eloquent\Model
     */
    public function __call($method, $args)
    {
        try {
            $buildScope = $this->_getScopeMethod($method);
            array_unshift($args, $this);
            return (new $this->calledFromModel)->$buildScope(...$args);
        } catch (\TypeError $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Make scope method
     * 
     * @param string $method
     * 
     * @return string
     */
    private function _getScopeMethod($method)
    {
        return 'scope' . ucfirst($method);
    }

    /**
     * Set with option
     * 
     * @param string|array $with
     * 
     * @return self
     */
    public function with($with)
    {
        $this->with = is_array($with) ? $with : func_get_args();
        return $this;
    }
}
