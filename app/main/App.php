<?php

require __DIR__ . '../Autoload.php';
use App\Main\Registry;

class App
{
    private $route;

    public function __construct($config)
    {
        new Autoload($config);
        Registry::getInstance()->config = $config;
        $this->route = new Route();
    }

    public function run()
    {
        $this->route->run();
    }
}
