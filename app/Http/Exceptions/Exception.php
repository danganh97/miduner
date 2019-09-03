<?php

namespace App\Http\Exceptions;

use App\Main\AppException;

class Exception extends AppException
{
    public function __construct($message, $code = null)
    {
        parent::__construct($message, $code);
    }
}