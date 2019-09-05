<?php

namespace App\Http\Exceptions;

use App\Main\Http\Request;
use App\Main\Http\Exceptions\ModelException as MidunerModelException;

class ModelException extends MidunerModelException
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