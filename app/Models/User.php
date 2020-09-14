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

    /**
     * Get full name attribute mutator
     * 
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return 'anh dep trai ' . $this->user_id;
    }

    /**
     * Get email attribute mutator
     * 
     * @return string
     */
    public function getEmailAttribute(): ?string
    {
        return $this->email;
    }

    /**
     * Get url social media attribute mutator
     * 
     * @return array
     */
    public function getUrlSocialAttribute(): array
    {
        return [
            'facebook' => 'facebook.com' . '/' . $this->user_id,
            'instagram' => 'instagram.com'
        ];
    }

    /**
     * Query scope me
     * 
     * @param QueryBuilder $query
     * 
     * @return QueryBuilder
     */
    public function scopeMe(QueryBuilder $query): QueryBuilder
    {
        return $query->where('user_id', '=', 3099);
    }

    /**
     * Query scope active
     * 
     * @param QueryBuilder $query
     * 
     * @return QueryBuilder
     */
    public function scopeActive(QueryBuilder $query): QueryBuilder
    {
        return $query->where('email', '=', 'admin@gmail.com');
    }

    /**
     * Option company
     * 
     * @return array
     */
    public function company(): array
    {
        return [
            'asdasd'=> 'asdasd',
            'xcvxcvvcx'=> 'asdasd'
        ];
    }

    /**
     * Relationship profile of user
     * 
     * @return object
     */
    public function profile(): object
    {
        return DB::table('user_profiles')->where('user_id', '=', $this->user_id)->first();
    }

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
