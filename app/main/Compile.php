<?php

namespace App\Main;

class Compile
{
    public function compileSelect($distinct)
    {
        return $distinct ? "SELECT DISTINCT " : "SELECT ";
    }

    public function compileColumns($columns)
    {
        return is_array($columns) ? implode(', ', $columns) : '*';
    }

    public function compileFrom($table)
    {
        return " FROM {$table}";
    }

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

    public function compileWheres(array $wheres)
    {
        $sql = " WHERE";
        foreach ($wheres as $key => $where) {
            $sql .= " $where[0] $where[1] '$where[2]'";
            if ($key < count($wheres) - 1) {
                $sql .= (strtolower($where[3] === 'and') ? ' AND' : ' OR');
            }
        }
        return $sql;
    }

    public function compileGroups($groups)
    {
        $sql = " GROUP BY " . implode(', ', $groups);
        return $sql;
    }

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

    public function compileLimit($limit)
    {
        return ' LIMIT ' .(int) $limit;
    }

    public function compileOffset($offset)
    {
        return " OFFSET {$offset}";
    }

    public function compileWhereIn($wherein)
    {
        return " WHERE {$wherein[0]} IN ({$wherein[1]})";
    }

    public function compileInsert($table, array $data)
    {
        foreach ($data as $key => $value) {
            $columns[] = $key;
            $values[] = "'$value'";
        }
        $columns = implode(', ', $columns);
        $values = implode(', ', $values);

        return "INSERT INTO $table($columns) VALUES ($values)";
    }
}
