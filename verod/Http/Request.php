<?php

namespace Midun\Http;

use Auth;
use Midun\Services\File;

class Request
{
    public function __construct()
    {
        foreach ($this->getRequest() as $key => $value) {
            $this->$key = $value;
        }
        foreach ($_FILES as $key => $value) {
            $this->$key = new File($value);
        }
    }

    /**
     * Make response array with the request data.
     *
     * @make array $request
     */
    public function getRequest()
    {
        $params = array_merge($_REQUEST, array_map(function ($file) {
            return new File($file);
        }, $_FILES));

        if ($this->method() === 'PUT') {
            parse_str(file_get_contents("php://input"), $data);
            $params = array_merge($params, $data);
        }

        return $params;
    }

    /**
     * Return response array with all request data.
     *
     * @return array $request
     */
    public function all()
    {
        return $this->getRequest();
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
    public function input($input)
    {
        return isset($this->getRequest()[$input]) ? $this->getRequest()[$input] : null;
    }

    /**
     * Response only 1 data input from param.
     * 
     * @param string $input
     * @return string input value
     */
    public function get($input)
    {
        return $this->input($input);
    }

    /**
     * Response only data input from array input.
     *
     * @param array $input
     * @return object input value
     */
    public function only($array_input)
    {
        foreach ($this->getRequest() as $name => $value) {
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
     * @return object
     */
    public function except(array $array_input)
    {
        $request = new \stdClass();
        foreach ($this->getRequest() as $name => $value) {
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

    /**
     * Get user from request
     * 
     * @param string $guard
     * 
     * @return mixed
     */
    public function user($guard = null)
    {
        if (is_null($guard)) {
            $guard = Auth::getCurrentGuard();
        }

        return Auth::guard($guard)->user();
    }

    /**
     * Return request is ajax request
     * 
     * @return bool
     */
    public function isAjax()
    {
        $headers = (array) $this->headers();
        return isset($headers['Accept'])
            && $headers['Accept'] == 'application/json'
            || isset($headers['Content-Type'])
            && $headers['Content-Type'] == 'application/json'
            || isset($headers['x-requested-with'])
            && $headers['x-requested-with'] == 'XMLHttpRequest';
    }

    /**
     * Get request server
     */
    public function server()
    {
        return $_SERVER;
    }

    /**
     * Get method
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
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
