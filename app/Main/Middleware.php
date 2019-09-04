<?php

namespace App\Main;

use App\Main\Routing\Compile;

class Middleware
{
    private $action;
    private $params;
    private $callback;

    public function __construct(Compile $callback, $action, $params = null)
    {
        $this->callback = $callback;
        $this->action = $action;
        $this->params = $params;
        return $this->handle($callback, $action, $params);
    }

    protected function next($action = null, $params = null)
    {
        if($action != null) {
            return (new $this->callback($action, $params));
        }
        return (new $this->callback($this->action, $this->params));
    }

}