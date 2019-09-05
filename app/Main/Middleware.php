<?php

namespace App\Main;

use App\Main\Routing\Compile;
use App\Http\Exceptions\Exception;

abstract class Middleware
{
    private $action;
    private $params;
    private $callback;

    public function __construct(Compile $callback, $action, $params = null)
    {
        $this->callback = $callback;
        $this->action = $action;
        $this->params = $params;
        if (method_exists($this, 'handle')) {
            return call_user_func_array([$this, 'handle'], [$callback, $action, $params]);
        }
        throw new Exception("Method handle not exists.");
    }

    protected function next($callback = null, $action = null, $params = null)
    {
        if ($callback != null) {
            return (new $callback($action, $params));
        }
        return (new $this->callback($this->action, $this->params));
    }

}