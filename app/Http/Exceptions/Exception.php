<?php

namespace App\Http\Exceptions;

use App\Main\Http\Exceptions\AppException;
use App\Main\Http\Request;

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