<?php

namespace Midun\Logger;

use Midun\Http\Exceptions\AppException;

class LoggerException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
