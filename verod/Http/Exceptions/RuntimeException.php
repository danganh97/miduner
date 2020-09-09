<?php

namespace Midun\Http\Exceptions;

class RuntimeException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
