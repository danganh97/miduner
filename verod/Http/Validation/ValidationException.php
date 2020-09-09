<?php

namespace Midun\Http\Validation;

use Midun\Http\Exceptions\AppException;

class ValidationException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
