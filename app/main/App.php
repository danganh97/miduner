<?php

require __DIR__ . '../Autoload.php';
use App\Main\Registry;

class App
{
    private $route;
    private static $controller;
    private static $action;

    public function __construct()
    {
        new Autoload();
        $this->route = new Route();
    }

    public function run()
    {
        $this->route->run();
    }
}
