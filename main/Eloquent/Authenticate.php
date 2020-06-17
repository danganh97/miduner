<?php

namespace Main\Eloquent;

use Main\Database\QueryBuilder\DB;

abstract class Authenticate extends Model
{
    protected $username;
    protected $password;
}
