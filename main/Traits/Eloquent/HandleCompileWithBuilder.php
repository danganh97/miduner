<?php

namespace Main\Traits\Eloquent;

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
    public static function staticEloquentBuilder($table, $modelMeta, $method, $args = null)
    {
        $object = isset($modelMeta['calledClass']) ? new self($table, $modelMeta['calledClass']) : new self($table);
        switch ($method) {
            case 'select':
                $select = $args && is_array($args[0]) ? $args[0] : $args;
                return $object->select($select);
            case 'addSelect':
                $select = $args && is_array($args[0]) ? $args[0] : $args;
                return $object->addSelect($select);
            case 'distinct':
                return $object->distinct();
            case 'join':
                list($tableJoin, $st, $operator, $nd) = $args;
                return $object->join($tableJoin, $st, $operator, $nd);
            case 'leftJoin':
                list($tableJoin, $st, $operator, $nd) = $args;
                return $object->leftJoin($tableJoin, $st, $operator, $nd);
            case 'rightJoin':
                list($tableJoin, $st, $operator, $nd) = $args;
                return $object->rightJoin($tableJoin, $st, $operator, $nd);
            case 'where':
                list($column, $operator, $value) = $args;
                return $object->where($column, $operator, $value);
            case 'orWhere':
                list($column, $operator, $value) = $args;
                return $object->orWhere($column, $operator, $value);
            case 'whereIn':
                list($column, $value) = $args;
                return $object->whereIn($column, $value);
            case 'whereNotIn':
                list($column, $value) = $args;
                return $object->whereNotIn($column, $value);
            case 'groupBy':
                $groupBy = $args && is_array($args[0]) ? $args[0] : $args;
                return $object->groupBy($groupBy);
            case 'having':
                list($column, $operator, $value) = $args;
                return $object->having($column, $operator, $value);
            case 'orHaving':
                list($column, $operator, $value) = $args;
                return $object->orHaving($column, $operator, $value);
            case 'orderBy':
                list($columns, $type) = $args;
                return $object->orderBy($columns, $type);
            case 'orderByDesc':
                list($columns) = $args;
                return $object->orderByDesc($columns);
            case 'latest':
                list($columns) = $args;
                return $object->latest($columns);
            case 'oldest':
                list($columns) = $args;
                return $object->oldest($columns);
            case 'limit':
                list($limit) = $args;
                return $object->limit($limit);
            case 'take':
                list($take) = $args;
                return $object->take($take);
            case 'skip':
                list($skip) = $args;
                return $object->skip($skip);
            case 'offset':
                list($offset) = $args;
                return $object->offset($offset);
            case 'get':
                return $object->get();
            case 'insert':
                list($data) = $args;
                return $object->insert($data);
            case 'create':
                list($data) = $args;
                return $object->create($data);
            case 'update':
                list($data) = $args;
                return $object->update($data);
            case 'find':
                list($value) = $args;
                $instance = self::calledModelInstance();
                $primaryKey = $instance->primaryKey();
                return $object->find($value, $primaryKey);
            case 'first':
                return $object->first();
            case 'findOrFail':
                list($value) = $args;
                return $object->findOrFail($value, $modelMeta['primaryKey']);
            case 'firstOrFail':
                return $object->firstOrFail();
            case 'delete':
                return $object->delete();
            case 'login':
                list($data) = $args;
                return $object->login($data);
            case 'with':
                $object->with = $args && is_array($args[0]) ? $args[0] : $args;
                return $object;
            case 'paginate':
                return $object->paginate(...$args);
            case 'when':
                return $object->when(...$args);
            default:
                return new self($table);
        }
        return true;
    }

    public function with($with)
    {
        $this->with = is_array($with) ? $with : func_get_args();
        return $this;
    }

}
