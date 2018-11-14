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

function request()
{
    return new \App\Http\Request;
}

function readDotENV()
{
    $app_url = dirname(dirname(__FILE__));
    $path = $app_url. '/.env';
    $handle = file_get_contents($path);
    $paze = explode("\n", $handle);
    foreach($paze as $key => $value){
        $vl[$key] = explode("=", $value);
        if(isset($vl[$key][0]) && isset($vl[$key][1])){
            $env[$vl[$key][0]] = $vl[$key][1];
        }
    }
    return $env;
}

function env($variable, $ndvalue = null)
{
    $env = readDotENV();
    foreach($env as $key => $value){
        if($variable == $key){
            $result = preg_replace('/\s+/', '', $value);
            if(is_null($result)){
                return $value;
            }
        }
    }
    return $ndvalue;
}

function config($variable)
{
    $app = require __DIR__.'/../config/app.php';
    foreach($app as $key => $value){
        if($variable == $key){
            return $value;
        }
    }
    return null;
}