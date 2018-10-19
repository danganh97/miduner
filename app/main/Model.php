<?php

namespace App\Main;

abstract class Model
{
    protected $table;

    // Tạm thời chưa viết vì chưa nghiên cứu Eloquent kỹ

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public static function ELQ()
    {
        //
    }

    public function getTable()
    {
        return $this->$table;
    }

    public static function __callStatic($method, $params)
    {
        return (new static )->$method($params);
    }
}
