<?php

namespace App\Models;

use DB;
use Main\Eloquent\Authenticate;

class User extends Authenticate
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $username = 'email';
    protected $password = 'password';

    protected $fillable = [
        'user_id', 'email', 'noti_push', 'role_id', 'is_verified', 'logged_at', 'email_token', 'remember_token',
    ];

    protected $hidden = [
        'password',
    ];

    protected $appends = [
        'full_name', 'url_social'
    ];

    protected $casts = [
        'user_id' => 'int',
        'email' => 'string',
    ];

    public function getFullNameAttribute()
    {
        return 'anh dep trai ' . $this->user_id;
    }

    public function getEmailAttribute()
    {
        return $this->email;
    }

    public function getUrlSocialAttribute()
    {
        return [
            'facebook' => 'facebook.com' . '/' . $this->user_id,
            'instagram' => 'instagram.com'
        ];
    }

    public function company()
    {
        return [
            'asdasd'=> 'asdasd',
            'xcvxcvvcx'=> 'asdasd'
        ];
    }

    public function profile()
    {
        return DB::table('user_profiles')->where('user_id', '=', $this->user_id)->first();
    }

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
