<?php

namespace Midun\Http\Middlewares;

use Midun\Http\Exceptions\AppException;

class MiddlewareException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
