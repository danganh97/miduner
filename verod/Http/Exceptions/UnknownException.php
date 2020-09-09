<?php

namespace Midun\Http\Exceptions;

class UnknownException extends AppException
{
    public function __construct($message = 'Unknown !', $code = 400)
    {
        parent::__construct($message, $code);
    }
}
