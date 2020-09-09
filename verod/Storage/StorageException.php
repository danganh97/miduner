<?php

namespace Midun\Storage;

use Midun\Http\Exceptions\AppException;

class StorageException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
