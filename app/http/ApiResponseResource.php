<?php

namespace App\Http;

header('Content-Type: application/json');

class ApiResponseResource
{
    public function handle($data)
    {
        echo json_encode($data);
    }

    public function message($message, $code = 200)
    {
        (new \App\Http\HttpResponseCode($code));
        $response['message'] = $message;
        echo json_encode($response);
    }
}
