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
        return ' LIMIT ' . (int) $limit;
    }

    public function compileOffset($offset)
    {
        return " OFFSET {$offset}";
    }

    public function compileWhereIn($wherein)
    {
        $sql = " WHERE {$wherein[0]} IN ";
        $array = explode(", ", $wherein[1]);
        foreach($array as $key => $arr){
            if($key + 1 == count($array)){
                $array[$key] = "'{$arr}'";
            }else{
                $array[$key] = "'{$arr}',";
            }
        }
        $string = implode("",$array);
        return " WHERE {$wherein[0]} IN ({$string})";
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

    public function compileLogin($table, array $cre)
    {
        foreach ($cre as $key => $dt) {
            $columns[] = $key;
            $values[] = "'$dt'";
        }
        return "SELECT * FROM {$table} WHERE {$columns[0]} = {$values[0]} AND {$columns[1]} = {$values[1]} LIMIT 1";
    }

    public function compileDelete($table)
    {
        return "DELETE FROM {$table}";
    }

    public function compileUpdate($table, array $arg)
    {
        $sql = "UPDATE {$table} SET ";
        foreach ($arg as $key => $dt) {
            $sql .= "$key = '$dt', ";
        }
        $lenght = strlen($sql);
        $sql = substr($sql, 0, $lenght - 2);
        return $sql;
    }
}
