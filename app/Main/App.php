<?php

require __DIR__ . '/Autoload.php';

use App\Main\Http\Exceptions\AppException;
use App\Main\Registry;

class App
{
    private $route;

    public function __construct($config)
    {
        new Autoload($config);
        $this->catchExceptionFatal();
        Registry::getInstance()->config = $config;
        $this->route = new Route();
        Registry::getInstance()->route = $this->route;
    }

    public function run()
    {
        return $this->route->run();
    }

    public function catchExceptionFatal()
    {
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error['type'] === E_ERROR) {
                echo $error['message'];
            }
        });
    }
}
