<?php

namespace Midun\View;

use Midun\Http\Exceptions\AppException;

class ViewException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
