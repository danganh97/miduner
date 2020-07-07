<?php

namespace Main\Traits\Eloquent;

use Main\Http\Exceptions\AppException;

trait HandleCompileWithBuilder
{
    public static $calledModelInstance;
    public $with;

    /**
     * Create new query builder from model
     *
     * @param ConnectionInterface $this->bindClass
     * return self
     */
    public function staticEloquentBuilder($table, $modelMeta, $method, $args = null)
    {
        $object = isset($modelMeta['calledClass']) ? new self($table, $modelMeta['calledClass']) : new self($table);
        switch ($method) {
            case 'find':
            case 'findOrFail':
                list($value) = $args;
                return $object->$method($value, $modelMeta['primaryKey']);
            case 'with':
                $object->with = $args && is_array($args[0]) ? $args[0] : $args;
                return $object;
            default:
                $buildScope = $this->_getScopeMethod($method);
                if (method_exists($modelMeta['calledClass'], $buildScope)) {
                    return $modelMeta['calledClass']::getInstance()->$buildScope($object);
                }
                throw new AppException("Method {$method} does not exist");
        }
    }

    public function __call($method, $args)
    {
        $buildScope = $this->_getScopeMethod($method);
        array_unshift($args, $this);
        return $this->calledFromModel::getInstance()->$buildScope(...$args);
    }

    private function _getScopeMethod($method)
    {
        return 'scope' . ucfirst($method);
    }

    public function with($with)
    {
        $this->with = is_array($with) ? $with : func_get_args();
        return $this;
    }

}
