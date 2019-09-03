<?php

namespace App\Http;

class Request
{
    public function __construct()
    {
        foreach(array_merge($_GET, $_POST) as $key => $value){
            $this->$key = $value;
        }
    }

    /**
     * Make response array with the request data.
     *
     * @make array $request
     */
    public static function getRequest()
    {
        return array_merge($_GET, $_POST);
    }

    /**
     * Return response array with all request data.
     *
     * @return array $request
     */
    public static function all()
    {
        $request = [];
        foreach (self::getRequest() as $name => $value) {
            $request[$name] = $value;
        }
        return $request;
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
        return $request;
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
        return $request;
    }
}
