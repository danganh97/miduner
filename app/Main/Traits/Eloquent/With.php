<?php

namespace App\Main\Traits\Eloquent;

trait With
{
    public function with(string $function)
    {
        $this->{strtolower($function)} = call_user_func([$this, $function]);
    }
}