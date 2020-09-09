<?php

namespace Midun\Database\DatabaseBuilder;

use Midun\Http\Exceptions\AppException;

class DatabaseBuilderException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
