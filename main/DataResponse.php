<?php

namespace Main;

use Main\Http\HttpResponseCode;

class DataResponse
{
    public static function json($arguments, $code = 200)
    {
        (new HttpResponseCode($code));
        return ApiResponse($arguments);
    }

    public static function toArray($arguments, $code = 200)
    {
        (new HttpResponseCode($code));
        dd($arguments);
    }
}