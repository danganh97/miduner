<?php

namespace App\Main\Traits\Eloquent;

trait With
{
    public function withExec(string $function, $instance)
    {
        $instance->{strtolower($function)} = call_user_func([$instance, $function]);
        return $instance;
    }
}