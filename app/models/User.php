<?php

namespace App\Models;

use App\Main\Model;

class User extends Model
{
    protected $table = 'users';

    public function __construct()
    {
        $this->setTable($this->table);
    }
}
