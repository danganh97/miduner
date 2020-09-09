<?php

namespace Midun\Http\Exceptions;

class UnauthorizedException extends AppException
{
    public function __construct($message = 'Unauthorized !', $code = 401)
    {
        parent::__construct($message, $code);
    }
}
