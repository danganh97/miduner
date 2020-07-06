<?php

namespace Main\Http;

use Main\Routing\Compile;
use Http\Exceptions\Exception;

abstract class Middleware
{
    private $action;
    private $params;
    private $callback;

    public abstract function handle(Closure $callback, $action, $params);

    public function __construct(Compile $callback, $action, $params = null)
    {
        $this->callback = $callback;
        $this->action = $action;
        $this->params = $params;
        return call_user_func_array([$this, 'handle'], [$callback, $action, $params]);
    }

    protected function next($callback = null, $action = null, $params = null)
    {
        if ($callback != null) {
            return (new $callback($action, $params));
        }
        return (new $this->callback($this->action, $this->params));
    }

}