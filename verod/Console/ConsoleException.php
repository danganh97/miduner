<?php

namespace Midun\Console;

use Midun\Http\Exceptions\AppException;

class ConsoleException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
