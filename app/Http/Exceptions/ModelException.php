<?php

namespace App\Http\Exceptions;

use Main\Http\Request;
use Main\Http\Exceptions\ModelException as MidunerModelException;

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