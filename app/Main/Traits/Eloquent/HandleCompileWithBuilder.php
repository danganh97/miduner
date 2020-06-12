<?php

namespace App\Main\Traits\Eloquent;

trait HandleCompileWithBuilder
{
    public static $calledModelInstance;

    /**
     * Create new query builder from model
     *
     * @param ConnectionInterface $this->bindClass
     * return self
     */
    public static function staticEloquentBuilder($table, $method, $args = null)
    {
        switch ($method) {
            case 'select':
                $select = $args && is_array($args[0]) ? $args[0] : $args;
                return (new self($table))->select($select);
            case 'addSelect':
                $select = $args && is_array($args[0]) ? $args[0] : $args;
                return (new self($table))->addSelect($select);
            case 'distinct':
                return (new self($table))->distinct();
            case 'join':
                list($tableJoin, $st, $operator, $nd) = $args;
                return (new self($table))->join($tableJoin, $st, $operator, $nd);
            case 'leftJoin':
                list($tableJoin, $st, $operator, $nd) = $args;
                return (new self($table))->leftJoin($tableJoin, $st, $operator, $nd);
            case 'rightJoin':
                list($tableJoin, $st, $operator, $nd) = $args;
                return (new self($table))->rightJoin($tableJoin, $st, $operator, $nd);
            case 'where':
                list($column, $operator, $value) = $args;
                return (new self($table))->where($column, $operator, $value);
            case 'orWhere':
                list($column, $operator, $value) = $args;
                return (new self($table))->orWhere($column, $operator, $value);
            case 'whereIn':
                list($column, $value) = $args;
                return (new self($table))->whereIn($column, $value);
            case 'whereNotIn':
                list($column, $value) = $args;
                return (new self($table))->whereNotIn($column, $value);
            case 'groupBy':
                $groupBy = $args && is_array($args[0]) ? $args[0] : $args;
                return (new self($table))->groupBy($groupBy);
            case 'having':
                list($column, $operator, $value) = $args;
                return (new self($table))->having($column, $operator, $value);
            case 'orHaving':
                list($column, $operator, $value) = $args;
                return (new self($table))->orHaving($column, $operator, $value);
            case 'orderBy':
                list($columns, $type) = $args;
                return (new self($table))->orderBy($columns, $type);
            case 'orderByDesc':
                list($columns) = $args;
                return (new self($table))->orderByDesc($columns);
            case 'latest':
                list($columns) = $args;
                return (new self($table))->latest($columns);
            case 'oldest':
                list($columns) = $args;
                return (new self($table))->oldest($columns);
            case 'limit':
                list($limit) = $args;
                return (new self($table))->limit($limit);
            case 'take':
                list($take) = $args;
                return (new self($table))->take($take);
            case 'skip':
                list($skip) = $args;
                return (new self($table))->skip($skip);
            case 'offset':
                list($offset) = $args;
                return (new self($table))->offset($offset);
            case 'get':
                return (new self($table))->get();
            case 'insert':
                list($data) = $args;
                return (new self($table))->insert($data);
            case 'create':
                list($data) = $args;
                return (new self($table))->create($data);
            case 'update':
                list($data) = $args;
                return (new self($table))->update($data);
            case 'find':
                list($value) = $args;
                $instance = self::calledModelInstance();
                $primaryKey = $instance->primaryKey();
                return (new self($table))->find($value, $primaryKey);
            case 'first':
                return (new self($table))->first();
            case 'findOrFail':
                list($value) = $args;
                $instance = self::calledModelInstance();
                $primaryKey = $instance->primaryKey();
                return (new self($table))->findOrFail($value, $primaryKey);
            case 'firstOrFail':
                return (new self($table))->firstOrFail();
            case 'delete':
                return (new self($table))->delete();
            case 'login':
                list($data) = $args;
                return (new self($table))->login($data);
        }
        return (new self($table))->$method($args);
    }
    /**
     * Get instance of called model
     *
     * @param ConnectionInterface $this->bindClass
     * return self
     */
    public static function getCalledModelInstance()
    {
        $calledModel = app()->callModel;
        if (!self::$calledModelInstance) {
            self::$calledModelInstance = (new $calledModel);
        }
        return self::$calledModelInstance;
    }
}
