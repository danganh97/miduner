<?php

namespace App\Main\Database\QueryBuilder;

use App\Main\Database\Connection;
use App\Http\Exceptions\Exception;
use App\Main\Traits\Eloquent\ExecuteQuery;
use App\Main\Database\QueryBuilder\Compile;
use App\Main\Traits\Eloquent\HandleCompileWithBuilder;

class DB
{
    use HandleCompileWithBuilder, ExecuteQuery;
    /**
     * The columns that should be returned.
     *
     * @var array
     */
    private $columns;

    /**
     * The table which the query is targeting.
     *
     * @var string
     */
    private $table;

    /**
     * Indicates if the query returns distinct results.
     *
     * @var bool
     */
    private $distinct = false;

    /**
     * The table joins for the query.
     *
     * @var array
     */
    private $joins;

    /**
     * The where constraints for the query.
     *
     * @var array
     */
    private $wheres;

    /**
     * The where constraints for the query.
     *
     * @var array
     */
    private $wherein;

    /**
     * The groupings for the query.
     *
     * @var array
     */
    private $groups;

    /**
     * The having constraints for the query.
     *
     * @var array
     */
    private $having;

    /**
     * The orderings for the query.
     *
     * @var array
     */
    private $orders;

    /**
     * The maximum number of records to return.
     *
     * @var int
     */
    private $limit;

    /**
     * The number of records to skip.
     *
     * @var int
     */
    private $offset;

    /**
     * Take 1 record
     *
     * @var b0olean
     */
    private $find = false;

    /**
     * Take 1 record
     *
     * @var boolean
     */
    private $first = false;

    /**
     * Fails throw Exception
     */
    private $isThrow = false;

    /**
     * Create a new query builder instance.
     *
     * @param  ConnectionInterface  $this->table
     * @return void
     */

    /**
     * Compile instance
     */
    private $compile;

    public function __construct($table)
    {
        $this->calledFromModel = app()->callModel;
        $this->table = $table;
        $this->compile = new Compile;
    }

    /**
     * Set the table which the query is targeting.
     *
     * @param  string  $table
     * @return $this
     */
    public static function table($table)
    {
        return new self($table);
    }

    /**
     * Create new query builder from model
     *
     * @param ConnectionInterface $this->bindClass
     * return self
     */
    public static function bindClass($class)
    {
        return new self((new $class)->table());
    }

    /**
     * Set the columns to be selected.
     *
     * @param  array|mixed  $columns
     * @return $this
     */
    public function select($columns)
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    /**
     * Add a new select column to the query.
     *
     * @param  array|mixed  $column
     * @return $this
     */
    public function addSelect($column)
    {
        $column = is_array($column) ? $column : func_get_args();

        $this->columns = array_merge((array) $this->columns, $column);

        return $this;
    }

    /**
     * Force the query to only return distinct results.
     *
     * @return $this
     */
    public function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    /**
     * Add a join clause to the query.
     *
     * @param  string  $table
     * @param  string  $first
     * @param  string  $operator
     * @param  string  $second
     * @param  string  $type
     * @param  bool    $where
     * @return $this
     */
    public function join($tableJoin, $st, $operator, $nd, $type = 'INNER')
    {
        $this->joins[] = [$tableJoin, $st, $operator, $nd, $type];
        return $this;
    }

    /**
     * Add a left join to the query.
     *
     * @param  string  $table
     * @param  string  $first
     * @param  string  $operator
     * @param  string  $second
     * @return \Database\QueryBuilder|static
     */
    public function leftJoin($tableJoin, $st, $operator = '=', $nd)
    {
        return $this->join($tableJoin, $st, $operator, $nd, 'LEFT');
    }

    /**
     * Add a right join to the query.
     *
     * @param  string  $table
     * @param  string  $first
     * @param  string  $operator
     * @param  string  $second
     * @return \Database\QueryBuilder|static
     */
    public function rightJoin($tableJoin, $st, $operator = '=', $nd)
    {
        return $this->join($tableJoin, $st, $operator, $nd, 'RIGHT');
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param  string|array|\Closure  $column
     * @param  string  $operator
     * @param  mixed   $value
     * @param  string  $boolean
     * @return $this
     */
    public function where($column, $operator = '=', $value = null, $boolean = 'AND')
    {
        if (!is_callable($column)) {
            $this->wheres[] = [$column, $operator, $value, $boolean];
            return $this;
        }
        $this->wheres[] = ['start_where'];
        call_user_func_array($column, [$this]);
        $this->wheres[] = ['end_where'];
        return $this;
    }

    /**
     * Add an "or where" clause to the query.
     *
     * @param  string|array|\Closure  $column
     * @param  string  $operator
     * @param  mixed   $value
     * @return \Database\QueryBuilder|static
     */
    public function orWhere($column, $operator = '=', $value = null)
    {
        if (!is_callable($column)) {
            return $this->where($column, $operator, $value, 'OR');
        }
        $this->wheres[] = ['start_or'];
        call_user_func_array($column, [$this]);
        $this->wheres[] = ['end_or'];
        return $this;
        // return $this->where($column, $operator, $value, 'OR');
    }

    /**
     * Add a "where in" clause to the query.
     *
     * @param  string  $column
     * @param  mixed   $values
     * @param  string  $boolean
     * @param  bool    $not
     * @return $this
     */
    public function whereIn($column, $value = [], $is = true)
    {
        $this->wherein = [$column, !is_array($value) ? $value : implode(', ', $value), $is];
        return $this;
    }

    /**
     * Add a "where not in" clause to the query.
     *
     * @param  string  $column
     * @param  mixed   $values
     * @param  string  $boolean
     * @return \Database\QueryBuilder|static
     */
    public function whereNotIn($column, $value = [])
    {
        return $this->whereIn($column, $value, false);
    }

    /**
     * Add a "group by" clause to the query.
     *
     * @param  array  ...$groups
     * @return $this
     */
    public function groupBy($columns)
    {
        $this->groups = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    /**
     * Add a "having" clause to the query.
     *
     * @param  string  $column
     * @param  string  $operator
     * @param  string  $value
     * @param  string  $boolean
     * @return $this
     */
    public function having($column, $operator = '=', $value, $boolean = 'and')
    {
        $this->havings[] = [$column, $operator, $value, $boolean];
        return $this;
    }

    /**
     * Add a "or having" clause to the query.
     *
     * @param  string  $column
     * @param  string  $operator
     * @param  string  $value
     * @return \Database\QueryBuilder|static
     */
    public function orHaving($column, $operator = '=', $value, $boolean = 'and')
    {
        return $this->having($column, $operator, $value, 'or');
    }

    /**
     * Add an "order by" clause to the query.
     *
     * @param  string  $column
     * @param  string  $direction
     * @return $this
     */
    public function orderBy($columns, $type = 'asc')
    {
        $this->orders[] = [$columns, $type];
        return $this;
    }

    /**
     * Add a descending "order by" clause to the query.
     *
     * @param  string  $column
     * @return $this
     */
    public function orderByDesc($column)
    {
        return $this->orderBy($column, 'desc');
    }

    /**
     * Add an "order by" clause for a timestamp to the query.
     *
     * @param  string  $column
     * @return \Database\QueryBuilder|static
     */
    public function latest($column = 'created_at')
    {
        return $this->orderBy($column, 'desc');
    }

    /**
     * Add an "order by" clause for a timestamp to the query.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function oldest($column = 'created_at')
    {
        return $this->orderBy($column, 'asc');
    }

    /**
     * Set the "limit" value of the query.
     *
     * @param  int  $value
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Alias to set the "limit" value of the query.
     *
     * @param  int  $value
     * @return \Database\QueryBuilder|static
     */
    public function take($value)
    {
        return $this->limit($value);
    }

    /**
     * Alias to set the "offset" value of the query.
     *
     * @param  int  $value
     * @return \Database\QueryBuilder|static
     */
    public function skip($value)
    {
        return $this->offset($value);
    }

    /**
     * Set the "offset" value of the query.
     *
     * @param  int  $value
     * @return $this
     */
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }
}
