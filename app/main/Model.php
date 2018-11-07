<?php

namespace App\Main;
use App\Main\QueryBuilder as DB;

abstract class Model
{
    protected $table;
    protected $primaryKey;
    protected $username;
    protected $password;

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function save()
    {
        return DB::table($this->table)->insert($this);
    }

    public function findStatic($param)
    {
        return DB::table($this->table)->find($this->primaryKey, $param);
    }

    public function createStatic($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function getStatic($column)
    {
        return DB::table($this->table)->select($column)->get();
    }

    public function loginStatic($data)
    {
        return $this->table == 'users' ? DB::table($this->table)->login([$this->username => $data[$this->username], $this->password => $data[$this->password]]) : false;
    }

    public static function create($data)
    {
        return (new static)->createStatic($data);
    }

    public static function find($param)
    {
        return (new static)->findStatic($param);
    }

    public static function get($column = ['*'])
    {
        return (new static)->getStatic($column);
    }

    public static function login($data)
    {
        return (new static)->loginStatic($data);
    }
}
