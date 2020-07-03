<?php

namespace Main\Eloquent;

use DB;

abstract class Authenticate extends Model
{
    protected $username;
    protected $password;
}
