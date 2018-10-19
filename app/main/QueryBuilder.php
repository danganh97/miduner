<?php

namespace App\Main;

use App\Main\Database;
use \PDO;

class QueryBuilder
{
    private $columns;
    private $table;
    private $distinct = false;
    private $joins;
    private $wheres;
    private $groups;
    private $having;
    private $orders;
    private $limit;
    private $offset;
    private $find = false;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public static function table($table)
    {
        return new self($table);
    }

    public function select($columns)
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    public function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    public function join($tableJoin, $st, $operator = '=', $nd, $type = 'inner')
    {
        $this->joins[] = [$tableJoin, $st, $operator, $nd, $type];
        return $this;
    }

    public function leftJoin($tableJoin, $st, $operator = '=', $nd)
    {
        return $this->join($tableJoin, $st, $operator, $nd, 'left');
    }

    public function rightJoin($tableJoin, $st, $operator = '=', $nd)
    {
        return $this->join($tableJoin, $st, $operator, $nd, 'right');
    }

    public function where($column, $operator = '=', $value, $boolean = 'and')
    {
        $this->wheres[] = [$column, $operator, $value, $boolean];
        return $this;
    }

    public function orWhere($column, $operator = '=', $value, $boolean = 'and')
    {
        return $this->where($column, $operator, $value, 'or');
    }

    public function groupBy($columns)
    {
        $this->groups = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    public function having($column, $operator = '=', $value, $boolean = 'and')
    {
        $this->havings[] = [$column, $operator, $value, $boolean];
        return $this;
    }

    public function orHaving($column, $operator = '=', $value, $boolean = 'and')
    {
        return $this->having($column, $operator, $value, 'or');
    }

    public function orderBy($columns, $type = 'asc')
    {
        $this->orders[] = [$columns, $type];
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function get()
    {
        if (!isset($this->table) || empty($this->table)) {
            return false;
        }
        $sql = $this->distinct ? "SELECT DISTINCT " : "SELECT ";
        if (isset($this->columns) && is_array($this->columns)) {
            $sql .= implode(', ', $this->columns);
        } else {
            $sql .= '*';
        }
        $sql .= " FROM {$this->table}";

        if (isset($this->joins) && is_array($this->joins)) {
            foreach ($this->joins as $join) {
                switch (strtolower($join[4])) {
                    case 'inner':
                        $sql .= ' INNER JOIN';
                        break;
                    case 'left':
                        $sql .= ' LEFT JOIN';
                        break;
                    case 'right':
                        $sql .= ' RIGHT JOIN';
                        break;
                    default:
                        $sql .= ' INNER JOIN';
                        break;
                }
                $sql .= " {$join[0]} ON {$join[1]} {$join[2]} {$join[3]}";
            }
        }

        if (isset($this->wheres) && is_array($this->wheres)) {
            $sql .= " WHERE";
            foreach ($this->wheres as $key => $where) {
                $sql .= " $where[0] $where[1] '$where[2]'";
                if ($key < count($this->wheres) - 1) {
                    $sql .= (strtolower($where[3] === 'and') ? ' AND' : ' OR');
                }
            }
        }

        if (isset($this->groups) && is_array($this->groups)) {
            $sql .= " GROUP BY " . implode(', ', $this->groups);
        }

        if (isset($this->havings) && is_array($this->havings)) {
            $sql .= " HAVING";
            foreach ($this->havings as $key => $having) {
                $sql .= " $having[0] $having[1] $having[2]";
                if ($key < count($this->havings) - 1) {
                    $sql .= (strtolower($having[3] === 'and') ? ' AND' : ' OR');
                }
            }
        }

        if (isset($this->orders) && is_array($this->orders)) {
            $sql .= " ORDER BY ";
            foreach ($this->orders as $key => $order) {
                $sql .= "$order[0] $order[1]";
                if ($key < count($this->orders) - 1) {
                    $sql .= ", ";
                }
            }
        }

        if (isset($this->limit)) {
            $sql .= " LIMIT $this->limit";
        }

        if (isset($this->offset)) {
            $sql .= " OFFSET $this->offset";
        }
        // echo $sql;
        // die();
        return $this->request($sql);
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

    public function find($column, $value)
    {
        $this->find = true;
        $sql = "SELECT ";
        if (isset($this->columns) && is_array($this->columns)) {
            $sql .= implode(', ', $this->columns);
        } else {
            $sql .= '*';
        }
        $sql .= " FROM $this->table where $column = '$value' LIMIT 1";
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
        $connection = (new Database)->connection();
        $object = $connection->prepare($sql);
        $object->execute();
        $type = explode(" ", $sql);
        switch ($type[0]) {
            case 'SELECT':
                return ($this->find === true) ? $object->fetch() : $object->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 'INSERT':
                return $this->find('id', $connection->lastInsertId());
                break;

            case 'UPDATE':
                return $this->find($this->wheres[0][0], $this->wheres[0][2]);
                break;
        }
        return $object;
    }
}
