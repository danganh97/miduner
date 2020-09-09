<?php

namespace Midun\Translator;

use Midun\Http\Exceptions\AppException;

class TranslationException extends AppException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
