<?php

namespace Main\Supports\Response;

use Main\Http\HttpResponseCode;

class DataResponse
{
    public static function json($arguments, $code = 200)
    {
        (new HttpResponseCode($code));
        header('Content-Type: application/json');
        echo json_encode($arguments);
        exit;
    }
}
