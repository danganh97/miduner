<?php

namespace Midun\Supports\Patterns\Interfaces;

interface RepositoryInterface
{
    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*']);

    /**
     * @param $column
     * @param null $key
     * @return mixed
     */
    public function lists($column, $key = null);

    /**
     * @param null $limit
     * @param array $columns
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*']);

    /**
     * Find
     *
     * @param $id
     * @param array $column
     * @return mixed
     */
    public function find($id, $column = ['*']);

    /**
     * Find or fail
     *
     * @param $id
     * @param array $column
     * @return mixed
     */
    public function findOrFail($id);

    /**
     * Where
     *
     * @param $condition
     * @param null $operator
     * @param null $value
     * @return mixed
     */
    public function where($condition, $operator = null, $value = null);

    /**
     * Or where
     *
     * @param $column
     * @param null $operator
     * @param null $value
     * @return mixed
     */
    public function orWhere($column, $operator = null, $value = null);

    /**
     * First or create
     *
     * @param $input
     * @return mixed
     */
    public function firstOrCreate($input);

    /**
     * Insert
     *
     * @param $input
     * @return mixed
     */
    public function insert($input);

    /**
     * Insert
     *
     * @param $input
     * @return mixed
     */
    public function create(array $input);

    /**
     * @param $id
     * @param $input
     * @return mixed
     */
    public function update($id, $input);

    /**
     * @param $column
     * @param $value
     * @param $input
     * @return mixed
     */
    public function multiUpdate($column, $value, $input);

    /**
     * @param $ids
     * @return mixed
     */
    public function delete($ids);

    /**
     * Load relations
     *
     * @param $relations
     * @return mixed
     */
    public function with($relations);

    /**
     * With
     *
     * @param $column
     * @param string $direction
     * @return mixed
     */
    public function orderBy($column, $direction = 'asc');

    /**
     * With count
     *
     * @param $relation
     * @return mixed
     */
    public function withCount($relation);

    /**
     * select
     *
     * @param array $columns
     * @return mixed
     */
    public function select($columns = ['*']);

    /**
     * Where has
     * @param $relation
     * @param $closure
     * @return mixed
     */
    public function whereHas($relation, $closure);

    /**
     * Where in
     *
     * @param $column
     * @param array $values
     *
     * @return $this
     */
    public function whereIn($column, array $values);

    /**
     * Where not in
     *
     * @param $column
     * @param array $values
     *
     * @return $this
     */
    public function whereNotIn($column, array $values);

    /**
     * Check if entity has relation
     *
     * @param string $relation
     *
     * @return $this
     */
    public function has($relation);

    /**
     * Join with other take
     * @param string $table
     * @param string $columnTableA
     * @param string $condition
     * @param string $columnTableB
     *
     * @return $this
     */
    public function join($table, $columnTableA = null, $condition = null, $columnTableB = null);

    /**
     * When function check condition to execute query
     * @param string $condition
     * @param Closure $callback
     * @param Closure $default
     *
     * @return $this
     */
    public function when($condition, $callback, $default = null);
}
