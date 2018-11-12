<?php

namespace App\Main;

use App\Main\Database;
use App\Main\Compile;
use \PDO;

class QueryBuilder
{
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
     * @var bolean
     */
    private $find = false;

    /**
     * Response to Sql
     *
     * @var bolean
     */
    private $toSql = false;


    /**
     * Create a new query builder instance.
     *
     * @param  \Illuminate\Database\ConnectionInterface  $this->table
     * @return void
     */
    public function __construct($table)
    {
        $this->connection = (new Database)->connection();
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
    public function join($tableJoin, $st, $operator, $nd, $type = 'inner')
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
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function leftJoin($tableJoin, $st, $operator = '=', $nd)
    {
        return $this->join($tableJoin, $st, $operator, $nd, 'left');
    }

    /**
     * Add a right join to the query.
     *
     * @param  string  $table
     * @param  string  $first
     * @param  string  $operator
     * @param  string  $second
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function rightJoin($tableJoin, $st, $operator = '=', $nd)
    {
        return $this->join($tableJoin, $st, $operator, $nd, 'right');
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
    public function where($column, $operator = '=', $value, $boolean = 'and')
    {
        $this->wheres[] = [$column, $operator, $value, $boolean];
        return $this;
    }

    /**
     * Add an "or where" clause to the query.
     *
     * @param  string|array|\Closure  $column
     * @param  string  $operator
     * @param  mixed   $value
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function orWhere($column, $operator = '=', $value, $boolean = 'and')
    {
        return $this->where($column, $operator, $value, 'or');
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
     * @return \Illuminate\Database\Query\Builder|static
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
     * @return \Illuminate\Database\Query\Builder|static
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
     * @return \Illuminate\Database\Query\Builder|static
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
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function take($value)
    {
        return $this->limit($value);
    }

    /**
     * Alias to set the "offset" value of the query.
     *
     * @param  int  $value
     * @return \Illuminate\Database\Query\Builder|static
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

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array  $columns
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        $sql = $this->paze();
        return $this->request($sql);
    }

    public function find($column, $value)
    {
        $this->where($column, '=', $value);
        $this->limit(1);
        $sql = $this->paze();
        return $this->request($sql);
    }

    public function toSql()
    {
        $sql = $this->paze();
        return $sql;
    }

    protected function paze()
    {
        if (!isset($this->table) || empty($this->table)) {
            return false;
        }
        $sql = $this->distinct ? "SELECT DISTINCT " : "SELECT ";
        $sql .= $this->compile->compileColumns($this->columns);
        $sql .= $this->compile->compileFrom($this->table);
        if (isset($this->joins) && is_array($this->joins)) {
            $sql .= $this->compile->compileJoins($this->joins);
        }
        if (isset($this->wheres) && is_array($this->wheres)) {
            $sql .= $this->compile->compileWheres($this->wheres);
        }
        if(isset($this->wherein)) {
            $sql .= $this->compile->compileWherein($this->wherein);
        }
        if (isset($this->groups) && is_array($this->groups)) {
            $sql .= $this->compile->compileGroups($this->groups);
        }
        if (isset($this->havings) && is_array($this->havings)) {
            $sql .= $this->compile->compileHavings($this->havings);
        }
        if (isset($this->orders) && is_array($this->orders)) {
            $sql .= $this->compile->compileOrders($this->orders);
        }
        if (isset($this->limit)) {
            $sql .= $this->compile->compileLimit($this->limit);
        }
        if (isset($this->offset)) {
            $sql .= $this->compile->compileOffset($this->offset);
        }
        return $sql;
    }

    public function insert($data = [])
    {
        foreach ($data as $key => $dt) {
            $columns[] = $key;
            $values[] = "'$dt'";
        }
        $columns = implode(', ', $columns);
        $values = implode(', ', $values);
        $sql = "INSERT INTO $this->table($columns)VALUES($values)";
        return $this->request($sql);
    }

    public function login($data = [])
    {
        $this->find = true;
        foreach ($data as $key => $dt) {
            $columns[] = $key;
            $values[] = "'$dt'";
        }
        $sql = "SELECT * FROM $this->table WHERE $columns[0] = $values[0] AND $columns[1] = $values[1] LIMIT 1";
        return $this->request($sql);
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table";
        if (isset($this->wheres) && is_array($this->wheres)) {
            $sql .= " WHERE";
            foreach ($this->wheres as $key => $where) {
                $sql .= " $where[0] $where[1] $where[2]";
                if ($key < count($this->wheres) - 1) {
                    $sql .= (strtolower($where[3] === 'and') ? ' AND' : ' OR');
                }
            }
        }
        return $this->request($sql);
    }

    public function update($data = [])
    {
        $sql = "UPDATE $this->table SET ";
        foreach ($data as $key => $dt) {
            $sql .= "$key = '$dt', ";
        }
        $lenght = strlen($sql);
        $sql = substr($sql, 0, $lenght - 2);
        if (isset($this->wheres) && is_array($this->wheres)) {
            $sql .= " WHERE";
            foreach ($this->wheres as $key => $where) {
                $sql .= " $where[0] $where[1] $where[2]";
                if ($key < count($this->wheres) - 1) {
                    $sql .= (strtolower($where[3] === 'and') ? ' AND' : ' OR');
                }
            }
        }
        return $this->request($sql);
    }

    public function request($sql)
    {
        $object = $this->connection->prepare($sql);
        $object->execute();
        $type = explode(" ", $sql);
        switch ($type[0]) {
            case 'SELECT':
                return ($this->find === true) ? $object->fetch() : $object->fetchAll(PDO::FETCH_ASSOC);
                break;
            case 'INSERT':
                return $this->find('id', $this->connection->lastInsertId());
                break;
            case 'UPDATE':
                return $this->find($this->wheres[0][0], $this->wheres[0][2]);
                break;
        }
        return $object;
    }
}
