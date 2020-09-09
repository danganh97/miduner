<?php

namespace Midun\Bus;

use Midun\Http\Exceptions\AppException;

class DispatcherException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
