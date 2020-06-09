<?php

namespace App\Models;

use App\Main\Eloquent\Authenticate;

class User extends Authenticate
{
    protected $table = 'user';
    protected $primaryKey = 'id';
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
        return 'anh deptrai ' . @$this->user_id;
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

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
