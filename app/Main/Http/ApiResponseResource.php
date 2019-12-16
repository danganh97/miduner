<?php

namespace App\Main\Http;

header('Content-Type: application/json');

class ApiResponseResource
{
    public function handle($data)
    {
        echo json_encode($data);
        exit;
    }

    public function message($message, $code = 200)
    {
        (new \App\Main\Http\HttpResponseCode($code));
        $response['message'] = $message;
        echo json_encode($response);
    }
}
