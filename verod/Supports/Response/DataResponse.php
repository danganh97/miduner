<?php

namespace Midun\Supports\Response;

use Midun\Http\HttpResponseCode;

class DataResponse
{
    /**
     * Response with json
     * 
     * @param mixed $arguments
     * @param int $code = 200
     * 
     * @return void
     */
    public final function json($arguments, $code = 200)
    {
        (new HttpResponseCode($code));
        header('Content-Type: application/json');

        if ($arguments instanceof \ArrayObject) {
            $arguments = objectToArray($arguments);
        }
        echo json_encode($arguments);
        exit;
    }
}
