<?php

namespace App\Main;

class DataResponse
{
    public static function json($arguments, $code = 200)
    {
        (new \App\Http\HttpResponseCode($code));
        return ApiResponse($arguments);
    }

    public static function toArray($arguments, $code = 200)
    {
        (new \App\Http\HttpResponseCode($code));
        echo '<pre>';
        print_r($arguments);
        echo '</pre>';
        return;
    }
}