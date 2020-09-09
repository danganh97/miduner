<?php

namespace Midun\Database\Connections\Mysql;

use Midun\Http\Exceptions\AppException;

class MysqlConnectionException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
