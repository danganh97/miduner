<?php

namespace Midun\Database\Connections\PostgreSQL;

use Midun\Http\Exceptions\AppException;

class PostgreConnectionException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
