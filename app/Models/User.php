<?php

namespace App\Models;

use DB;
use Midun\Database\QueryBuilder\QueryBuilder;
use Midun\Eloquent\Authenticate;

class User extends Authenticate
{
    protected string $table = 'users';
    protected string $primaryKey = 'user_id';
    protected string $username = 'email';
    protected string $password = 'password';

    protected array $fillable = [
        'user_id', 'email', 'noti_push', 'role_id', 'is_verified', 'logged_at', 'email_token', 'remember_token',
    ];

    protected array $hidden = [
        'password',
    ];

    protected array $appends = [
        'full_name', 'url_social'
    ];

    protected array $casts = [
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

    public function scopeMe(QueryBuilder $query)
    {
        return $query->where('user_id', '=', 3099);
    }

    public function scopeActive(QueryBuilder $query)
    {
        return $query->where('email', '=', 'admin@gmail.com');
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
