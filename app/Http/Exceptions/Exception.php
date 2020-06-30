<?php

namespace App\Http\Exceptions;

use Main\Http\Exceptions\AppException;

class Exception extends AppException
{

    public function render($exception)
    {
        parent::render($exception);
    }

    public function report()
    {
        return true;
    }
}