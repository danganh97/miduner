<?php

namespace Midun\Routing;

use Midun\Http\Exceptions\AppException;

class RouteException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
