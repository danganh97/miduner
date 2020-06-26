<?php

use Main\Registry;
use Main\Http\Exceptions\AppException;
require_once dirname(dirname(__FILE__)) . '/App.php';

if (!function_exists('redirect')) {
    function redirect($url)
    {
        header('Location: ' . $url);
    }
}

if (!function_exists('view')) {
    function view($view, $data = null)
    {
        return (new Main\Eloquent\Controller)->render($view, $data);
    }
}

if (!function_exists('simpleView')) {
    function simpleView($view, $data = null)
    {
        return (new Main\Eloquent\Controller)->render($view, $data);
    }
}

if (!function_exists('response')) {
    function response()
    {
        return new \Main\DataResponse;
    }
}

if (!function_exists('ApiResponse')) {
    function ApiResponse($data)
    {
        return (new Main\Http\ApiResponseResource)->handle($data);
    }
}

if (!function_exists('sendMessage')) {
    function sendMessage($message, $code = null)
    {
        return (new Main\Http\ApiResponseResource)->message($message, $code);
    }
}

if (!function_exists('session')) {
    function session($key, $value = null)
    {
        $session = (new Main\Session);
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
        return Main\Session::unset_session($key);
    }
}

if (!function_exists('request')) {
    function request()
    {
        return new \App\Http\Requests\Request;
    }
}

if (!function_exists('readDotENV')) {
    function readDotENV()
    {
        $app_base = dirname(dirname(dirname(__FILE__)));
        $path = $app_base . '/.env';
        if (!file_exists($path)) {
            system("echo " . 'Missing .env file.');
            exit;
        }
        return parse_ini_file($path);
    }
}
if (!function_exists('env')) {
    function env($variable, $ndvalue = null)
    {
        $base_path = dirname(dirname(dirname(__FILE__)));
        $path = $base_path . '/cache/environments.php';
        if (!file_exists($path)) {
            system("echo " . 'Missing environment file.');
            exit;
        }
        $env = include $path;
        foreach ($env as $key => $value) {
            if ($variable == $key) {
                $result = $value;
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
        $pase = explode('.', $variable);
        $base_path = dirname(dirname(dirname(__FILE__)));
        $path = $base_path . '/cache/' . $pase[0] . '.php';
        if (!file_exists($path)) {
            system("echo " . "file $pase[0] not found in cache.");
            exit;
        }
        array_shift($pase);
        $configs = include $path;
        $initValue = $configs[$pase[0]];
        array_shift($pase);
        if(empty($pase)) {
            return $initValue;
        }
        foreach($pase as $p) {
            if(!$initValue[$p]) {
                die("Variable $variable not found.");
            }
            $value = $initValue = $initValue[$p];
        }
        return $value;
    }
}

if(!function_exists('trans')) {
    function trans($variable, $params = [], $lang = 'en')
    {
        $variable = explode('.', $variable);
        $file = array_shift($variable);
        $root = dirname(dirname(dirname(__FILE__)));
        $configs = require "$root/resources/lang/$file/$lang.php";
        $initValue = $configs[$variable[0]];
        $needValue = '';
        array_shift($variable);
        if(empty($variable)) {
            $needValue = $initValue;
        } else {
            foreach($variable as $p) {
                if(!$initValue[$p]) {
                    die("Variable $variable not found.");
                }
                $needValue = $initValue = $initValue[$p];
            }
        }
        foreach($params as $key => $param) {
            $needValue = str_replace(":$key", $param, $needValue);
        }
        return $needValue;
    }
}

if(!function_exists('__')) {
    function __($variable, $lang = 'en')
    {

    }
}

if (!function_exists('action')) {
    function action($action, array $params = null)
    {
        return Registry::getInstance()->route->callableAction($action, $params);
    }
}

if (!function_exists('route')) {
    function route(string $name)
    {
        $routes = app()->routes;
        $flag = false;
        $uri = '';
        foreach ($routes as $key => $route) {
            if (strtolower($name) === strtolower($route['name'])) {
                $flag = true;
                $uri = $route['uri'];
            }
        }
        if ($flag === true) {
            echo $uri;
        } else {
            throw new Exception("The route " . '"' . $name . '"' . " doesn't exists");
        }
    }
}

if (!function_exists('toPre')) {
    function toPre($collection): void
    {
        print_r($collection);
        exit;
    }
}

if (!function_exists('app')) {
    function app()
    {
        return App::getInstance();
    }
}

if (!function_exists('is_json')) {
    function is_json($argument)
    {
        return (json_decode(json_encode($argument)) != NULL) ? true : false;
    }
}

if (!function_exists('dd')) {
    function dd()
    {
        array_map(function($x) { 
            print_r($x); 
        }, func_get_args());
        die;
    }
}

if (!function_exists('assets')) {
    function assets($path)
    {
        if (php_sapi_name() == 'cli-server') {
            return "/public/$path";
        } else {
            return $path;
        }
        throw new Exception("");
    }
}

if (!function_exists('included')) {
    function included($path)
    {
        $path = config('app.base') . '/resources/views/' . str_replace('.', '/', $path) . '.php';
        if (file_exists($path)) {
            include($path);
        } else {
            throw new AppException("File $path not found.");
        }
    }
}
