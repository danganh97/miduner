<?php

if (!function_exists('redirect')) {
    function redirect($url)
    {
        header('Location: ' . $url);
    }
}

if (!function_exists('view')) {
    function view($view, $data = null)
    {
        return (new App\Main\Controller)->render($view, $data);
    }
}

if (!function_exists('simpleView')) {
    function simpleView($view, $data = null)
    {
        return (new App\Main\Controller)->singleRender($view, $data);
    }
}

if (!function_exists('response')) {
    function response()
    {
        return new \App\Main\DataResponse;
    }
}

if (!function_exists('ApiResponse')) {
    function ApiResponse($data)
    {
        return (new App\Http\ApiResponseResource)->handle($data);
    }
}

if (!function_exists('sendMessage')) {
    function sendMessage($message, $code = null)
    {
        return (new App\Http\ApiResponseResource)->message($message, $code);
    }
}

if (!function_exists('session')) {
    function session($key, $value = null)
    {
        $session = (new App\Main\Session);
        if ($value !== null) {
            $session->$key = $value;
            return true;
        } elseif ($value === null) {
            return $session->$key;
        }
        die('Syntax wrong !');
    }
}

if (!function_exists('unsetsession')) {
    function unsetsession($key)
    {
        return App\Main\Session::unset_session($key);
    }
}

if (!function_exists('request')) {
    function request()
    {
        return new \App\Http\Request;
    }
}

if (!function_exists('readDotENV')) {
    function readDotENV()
    {
        $app_url = dirname(dirname(__FILE__));
        $path = $app_url . '/.env';
        $handle = file_get_contents($path);
        $paze = explode("\n", $handle);
        foreach ($paze as $key => $value) {
            $vl[$key] = explode("=", $value);
            if (isset($vl[$key][0]) && isset($vl[$key][1])) {
                $env[$vl[$key][0]] = $vl[$key][1];
            }
        }
        return $env;
    }
}

if (!function_exists('env')) {
    function env($variable, $ndvalue = null)
    {
        $env = readDotENV();
        foreach ($env as $key => $value) {
            if ($variable == $key) {
                $result = preg_replace('/\s+/', '', $value);
                if (!empty($result)) {
                    return $result;
                }
                break;
            }
        }
        return $ndvalue;
    }
}

if (!function_exists('config')) {
    function config($variable)
    {
        $paze = explode('.', $variable);
        if (count($paze) != 2) {
            throw new \App\Main\AppException("The {$variable} doesn't exists !");
        }
        if (!$url = \App\Main\Registry::getInstance()->config['appurl'] . "/config/{$paze[0]}.php") {
            throw new \App\Main\AppException("The $url doesn't exists !");
        } else {
            $config = require $url;
        }
        return $config[$paze[1]];
    }
}
