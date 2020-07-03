<?php

namespace Main\Eloquent;

use Hash;
use DB;

abstract class Authenticate extends Model
{
    public function setPasswordAttribute($password)
    {
        return Hash::make($password);
    }
}
