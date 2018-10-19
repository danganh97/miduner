<?php

require __DIR__ . '../Autoload.php';
use App\Main\Registry;

class App
{
    private $route;
    private static $controller;
    private static $action;

    public function __construct($config)
    {
        new Autoload($config['APP_URL'], $config['AUTO_LOAD']);
        $this->route = new Route($config['BASE_URL']);
        Registry::getInstance()->config = $config;
    }

    public function run()
    {
        $this->route->run();
    }
}
