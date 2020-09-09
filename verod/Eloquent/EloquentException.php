<?php

namespace Midun\Eloquent;

use Midun\Http\Exceptions\AppException;

class EloquentException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
