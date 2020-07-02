<?php

namespace Main\Http;

use Main\Services\File;
use Main\Traits\Instance;

abstract class Request
{
    use Instance;

    public function __construct()
    {
        foreach(self::getRequest() as $key => $value){
            $this->$key = $value;
        }
        foreach($_FILES as $key => $value){
            $this->$key = new File($value);
        }
    }

    /**
     * Make response array with the request data.
     *
     * @make array $request
     */
    public static function getRequest()
    {
        return array_merge($_REQUEST, array_map(function ($file) {
            return new File($file);
        }, $_FILES));
    }

    /**
     * Return response array with all request data.
     *
     * @return array $request
     */
    public static function all()
    {
        return self::getRequest();
    }

    /**
     * Get all query parameters
     */
    public function getQueryParams()
    {
        return $_GET;
    }

    /**
     * Response only 1 data input from param.
     *
     * @param string $input
     * @return string input value
     */
    public static function input($input)
    {
        return isset(self::getRequest()[$input]) ? self::getRequest()[$input] : null;
    }

    /**
     * Response only 1 data input from param.
     * 
     * @param string $input
     * @return string input value
     */
    public function get($input)
    {
        return self::input($input);
    }

    /**
     * Response only data input from array input.
     *
     * @param array $input
     * @return array string input value
     */
    public static function only($array_input)
    {
        foreach (self::getRequest() as $name => $value) {
            if (in_array($name, $array_input)) {
                $request[$name] = $value;
            }
        }
        return (object) $request;
    }

    /**
     * Response data input except array input.
     *
     * @param array $input
     * @return array string input value
     */
    public static function except($array_input)
    {
        foreach (self::getRequest() as $name => $value) {
            if (!in_array($name, $array_input)) {
                $request[$name] = $value;
            }
        }
        return (object) $request;
    }

    /**
     * Get all headers requested
     */
    public function headers()
    {
        return (object) getallheaders();
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return isset($this->$name) ? $this->name : null;
    }
}
