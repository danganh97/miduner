<?php

namespace Midun\Auth;

use Midun\Http\Exceptions\AppException;

class AuthenticationException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
