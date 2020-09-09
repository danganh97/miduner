<?php

namespace Midun\Http\Exceptions;

use Midun\Application;
use Midun\Container;

class ErrorHandler
{
    private $log;

    public function __construct()
    {
        $this->app = Container::getInstance();

        if ($this->app->make(Application::class)->isLoaded()) {
            $this->app->make('view')->setMaster('');
            ob_get_clean();
            $this->log = $this->app->make('log');
        }
    }

    public function errorHandler($errno, $errstr, $file, $line)
    {
        $msg = "{$errstr} on line {$line} in file {$file}";

        if (!isset($this->log)) {
            die($msg);
        }

        $file = str_replace(base_path(), '', $file);

        $this->log->error($msg);

        $e = new UnknownException($msg);

        $e->render($e);

        exit($errno);
    }
}
