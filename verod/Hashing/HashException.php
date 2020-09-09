<?php

namespace Midun\Hashing;

use Midun\Http\Exceptions\AppException;

class HashException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
