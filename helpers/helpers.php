<?php

function redirect($url)
{
    header('Location: ' . $url);
}

function view($view, $data = null)
{
    return (new App\Main\Controller)->render($view, $data);
}

function simpleView($view, $data = null)
{
    return (new App\Main\Controller)->singleRender($view, $data);
}

function response($data, $code = 200)
{
    (new App\Http\HttpResponseCode($code));
    return ApiResponse($data);
}

function ApiResponse($data)
{
    return (new App\Http\ApiResponseResource)->handle($data);
}

function sendMessage($message, $code = null)
{
    return (new App\Http\ApiResponseResource)->message($message, $code);
}

function session($key, $value = null)
{
    $session = (new App\Main\Session);
    if($value !== null)
    {
        $session->$key = $value;
        return true;
    }elseif($value === null)
    {
        return $session->$key;
    }
    die('Syntax wrong !');
}

function unsetsession($key)
{
    return App\Main\Session::unset_session($key);
}
