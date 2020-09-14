<?php

namespace App\Models;

use Midun\Eloquent\Model;

class UserProfile extends Model
{
    protected string $table = 'user_profiles';
    protected string $primaryKey = 'user_id';

}
