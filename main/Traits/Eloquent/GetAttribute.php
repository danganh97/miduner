<?php

namespace Main\Traits\Eloquent;

trait GetAttribute
{
    public function table()
    {
        return $this->table;
    }

    public function password()
    {
        return $this->password;
    }

    public function primaryKey()
    {
        return $this->primaryKey;
    }

    public function createdAt()
    {
        return self::CREATED_AT;
    }

    public function updatedAt()
    {
        return self::UPDATED_AT;
    }

    public function appends()
    {
        return $this->appends;
    }

    public function fillable()
    {
        return $this->fillable;
    }

    public function casts()
    {
        return $this->casts;
    }

    public function hidden()
    {
        return $this->hidden;
    }
}