<?php

namespace App\Http\Exceptions;

use Main\Http\Exceptions\AppException;
use Main\Http\Request;

class Exception extends AppException
{

    public function render($exception, Request $request = null)
    {
        parent::render($exception, $request);
    }

    public function report()
    {
        return true;
    }
}