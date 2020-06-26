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
     * Response only 1 data input from param.
     *
     * @param string $input
     * @return string input value
     */
    public static function input($input)
    {
        foreach (self::getRequest() as $name => $value) {
            if ($name == $input) {
                return $value;
            }
        }
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
}
