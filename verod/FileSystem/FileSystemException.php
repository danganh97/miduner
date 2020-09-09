<?php

namespace Midun\FileSystem;

use Midun\Http\Exceptions\AppException;

class FileSystemException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
