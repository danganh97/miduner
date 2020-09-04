<?php

namespace App\Models;

use Midun\Eloquent\Model;

class Partner extends Model
{
    protected $table = 'partner';
    protected $primaryKey = 'pid';
    protected $username = 'email';
    protected $password = 'password';

    protected $fillable = [
      'pid', 'pm_name', 'pm_id', 'pm_phone', 'email', 'p_image', 'pc_name', 'pc_image', 'pc_tob', 'pc_phone', 'pc_address', 'pc_brand', 'pc_introduction', 'pc_state', 'active', 'first_login'
    ];

    protected $hidden = [
      'password'
    ];

    protected $appends = [
      'full_name'
    ];

    protected $casts = [
      'pid' => 'string',
      'pc_state' => 'boolean',
    ];

    public function getFullNameAttribute()
    {
        return 'vo dang anh';
    }

    public function getPcNameAttribute()
    {
        return 'vo dang anh ne';
    }

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
