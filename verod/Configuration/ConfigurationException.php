<?php

namespace Midun\Configuration;

use Midun\Http\Exceptions\AppException;

class ConfigurationException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
