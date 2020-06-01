<?php

namespace App\Main\Eloquent;

use App\Main\Database\QueryBuilder\DB;

abstract class Authenticate extends Model
{
    protected $username;
    protected $password;

    public function loginStatic($data)
    {
        return $this->table == 'users' ? DB::table($this->table)->login([$this->username => $data[$this->username], $this->password => $data[$this->password]]) : false;
    }

    public static function login($data)
    {
        return (new static )->loginStatic($data);
    }

}
