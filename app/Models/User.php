<?php

namespace App\Models;

use Midun\Eloquent\Authenticate;
use Midun\Eloquent\Relationship\Relation;
use Midun\Database\QueryBuilder\QueryBuilder;

class User extends Authenticate
{
    protected string $primaryKey = 'id';
    protected string $username = 'email';
    protected string $password = 'password';

    protected array $fillable = [
        'id', 'email', 'name', 'dob', 'is_active'
    ];

    protected array $hidden = [
        'password',
    ];

    protected array $appends = [
        'full_name', 'url_social'
    ];

    protected array $casts = [
        'id' => 'int',
        'email' => 'string',
    ];

    /**
     * Get full name attribute mutator
     * 
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return 'You are so handsome: ' . $this->id;
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
            'facebook' => 'facebook.com' . '/' . $this->id,
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
        return $query->where('id', '=', $this->id);
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
        return $query->where('is_active', '=', 1);
    }

    /**
     * Relationship profile of user
     * 
     * @return Relation
     */
    public function profile(): Relation
    {
        return $this->hasOne(UserProfile::class, "id", "user_id")->where('is_active', 1);
    }

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
