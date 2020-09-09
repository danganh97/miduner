<?php

namespace Midun\Traits\Eloquent;

trait GetAttribute
{
    /**
     * Get table
     * 
     * @return string
     */
    public function table()
    {
        return $this->table;
    }

    /**
     * Get password
     * 
     * @return string
     */
    public function password()
    {
        return $this->password;
    }

    /**
     * Get primary key
     * 
     * @return string
     */
    public function primaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * Get created at
     * 
     * @return string
     */
    public function createdAt()
    {
        return self::CREATED_AT;
    }

    /**
     * Get updated at
     * 
     * @return string
     */
    public function updatedAt()
    {
        return self::UPDATED_AT;
    }

    /**
     * Get list appends
     * 
     * @return array
     */
    public function appends()
    {
        return $this->appends;
    }

    /**
     * Get list fillable
     * 
     * @return array
     */
    public function fillable()
    {
        return $this->fillable;
    }

    /**
     * Get list casts
     * 
     * @return array
     */
    public function casts()
    {
        return $this->casts;
    }

    /**
     * Get list hidden
     * 
     * @return array
     */
    public function hidden()
    {
        return $this->hidden;
    }
}