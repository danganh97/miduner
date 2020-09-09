<?php

namespace Midun\Database\QueryBuilder;

use Midun\Http\Exceptions\AppException;

class QueryException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
