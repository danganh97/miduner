<?php

namespace Midun\Database\QueryBuilder;

use Midun\Eloquent\Model;

class Compile
{
    /**
     * Compile select distinct
     * 
     * @param bool $distinct
     * 
     * @return string
     */
    public function compileSelect(bool $distinct)
    {
        return $distinct ? "SELECT DISTINCT " : "SELECT ";
    }

    /**
     * Compile select columns
     * 
     * @param array|null $columns
     * 
     * @return string
     */
    public function compileColumns($columns)
    {
        return is_array($columns) ? implode(', ', $columns) : '*';
    }

    /**
     * Compile from
     * 
     * @param string $table
     * 
     * @return string
     */
    public function compileFrom(string $table)
    {
        return " FROM {$table}";
    }

    /**
     * Compile join
     * 
     * @param array $joins
     * 
     * @return string
     */
    public function compileJoins(array $joins)
    {
        foreach ($joins as $join) {
            switch (strtolower($join[4])) {
                case 'inner':
                    $sql = ' INNER JOIN';
                    break;
                case 'left':
                    $sql = ' LEFT JOIN';
                    break;
                case 'right':
                    $sql = ' RIGHT JOIN';
                    break;
                default:
                    $sql = ' INNER JOIN';
                    break;
            }
            $sql .= " {$join[0]} ON {$join[1]} {$join[2]} {$join[3]}";
        }
        return $sql;
    }

    /**
     * Compile where
     * 
     * @param array $wheres
     * 
     * @return string
     */
    public function compileWheres(array $wheres)
    {
        $sql = " WHERE";
        foreach ($wheres as $key => $where) {
            if ($key == 0) {
                if ($where[0] == 'start_where') {
                    $sql .= ' (';
                }
                if ($where[0] == 'start_or') {
                    $sql .= ' (';
                }
            } else {
                if ($where[0] == 'start_where') {
                    $sql .= ' AND (';
                }
                if ($where[0] == 'start_or') {
                    $sql .= ' OR (';
                }
            }

            if ($where[0] == 'end_where' || $where[0] == 'end_or') {
                $sql .= ') ';
            }
            if ($key == 0) {
                if ($where[0] !== 'start_where' && $where[0] !== 'end_where') {
                    $sql .= " {$where[0]} {$where[1]} ?";
                }
            } else {
                if ($wheres[$key - 1][0] !== 'start_where') {
                    if ($where[0] !== 'start_where' && $where[0] !== 'end_where' && $where[0] !== 'start_or' && $where[0] !== 'end_or') {
                        if ($wheres[$key - 1][0] !== 'start_or') {
                            $sql .= " $where[3] $where[0] $where[1] ?";
                        } else {
                            $sql .= " $where[0] $where[1] ?";
                        }
                    }
                } else {
                    if ($where[0] !== 'start_where' && $where[0] !== 'end_where' && $key != 0) {
                        $sql .= "$where[0] $where[1] ?";
                    }
                }
            }
        }
        return $sql;
    }

    /**
     * Compile group by
     * 
     * @param array $groups
     * 
     * @return string
     */
    public function compileGroups(array $groups)
    {
        $sql = " GROUP BY " . implode(', ', $groups);
        return $sql;
    }

    /**
     * Compile having
     * 
     * @param array $havings
     * 
     * @return string
     */
    public function compileHavings(array $havings)
    {
        $sql = " HAVING";
        foreach ($havings as $key => $having) {
            $sql .= " $having[0] $having[1] $having[2]";
            if ($key < count($havings) - 1) {
                $sql .= (strtolower($having[3] === 'and') ? ' AND' : ' OR');
            }
        }
        return $sql;
    }

    /**
     * Compile order
     * 
     * @param array $orders
     * 
     * @return string
     */
    public function compileOrders(array $orders)
    {
        $sql = " ORDER BY ";
        foreach ($orders as $key => $order) {
            $sql .= "$order[0] $order[1]";
            if ($key < count($orders) - 1) {
                $sql .= ", ";
            }
        }
        return $sql;
    }

    /**
     * Compile limit
     * 
     * @param int $limit
     * 
     * @return string
     */
    public function compileLimit(int $limit)
    {
        return " LIMIT {$limit}";
    }

    /**
     * Compile offset
     * 
     * @param int $offset
     * 
     * @return string
     */
    public function compileOffset(int $offset)
    {
        return " OFFSET {$offset}";
    }

    /**
     * Compile where in
     * 
     * @param array $wherein
     * 
     * @return string
     */
    public function compileWhereIn(array $wherein)
    {
        $array = explode(", ", $wherein[1]);
        foreach ($array as $key => $arr) {
            if ($key + 1 == count($array)) {
                $array[$key] = "'{$arr}'";
            } else {
                $array[$key] = "'{$arr}',";
            }
        }
        $string = implode("", $array);
        return " WHERE {$wherein[0]} IN ({$string})";
    }

    /**
     * Compile insert
     * 
     * @param string $table
     * @param array $data
     * 
     * @return string
     */
    public function compileInsert($table, array $data)
    {
        $columns = [];
        $values = [];

        foreach ($data as $key => $value) {
            $columns[] = $key;
            $values[] = "'$value'";
        }

        $columns = implode(', ', $columns);
        $values = implode(', ', $values);

        return "INSERT INTO $table($columns) VALUES ($values)";
    }

    /**
     * Compile create
     * 
     * @param Model $model
     * @param array $fillable
     * @param array $data
     * 
     * @return string
     * 
     * @throws QueryException
     */
    public function compileCreate(Model $model, array $fillable, array $data)
    {
        try {
            $columns = [];
            $values = [];
            foreach ($fillable as $column) {
                if (isset($data[$column])) {
                    $ucFirst = ucfirst($column);
                    $settingMethod = "set{$ucFirst}Attribute";
                    if (method_exists($model, $settingMethod)) {
                        $values[] = "'" . call_user_func([$model, $settingMethod], $data[$column]) . "'";
                    } else {
                        $values[] = "'$data[$column]'";
                    }
                    $columns[] = $column;
                }
            }
            $columns = implode(', ', $columns);
            $values = implode(', ', $values);
            $table = $model->table();
            return "INSERT INTO $table($columns) VALUES ($values)";
        } catch (\Exception $e) {
            throw new QueryException($e->getMessage());
        }
    }

    /**
     * Compile delete
     * 
     * @param string $table
     * 
     * @return string
     */
    public function compileDelete($table)
    {
        return "DELETE FROM {$table}";
    }

    /**
     * Compile update
     * 
     * @param string $table
     * @param array $arg
     * 
     * @return string
     */
    public function compileUpdate($table, array $arg)
    {
        $sql = "UPDATE {$table} SET ";
        foreach ($arg as $key => $dt) {
            $sql .= "$key = '$dt', ";
        }
        $length = strlen($sql);
        $sql = substr($sql, 0, $length - 2);
        return $sql;
    }
}
