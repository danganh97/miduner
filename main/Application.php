<?php

namespace Main;

use Main\Autoload;
use Main\Routing\Route;

class Application
{
    private $route;

    public function __construct()
    {
        $this->setRouter();
        $this->registerServiceProvider(config('app.providers'));
        $this->collectAutoload();
    }

    /**
     * Set router
     *
     * @return void
     */
    private function setRouter()
    {
        $this->route = new Route();
    }

    private function registerServiceProvider($providers = [])
    {
        foreach ($providers as $provider) {
            $provider = new $provider;
            $provider->handle();
        }
    }

    /**
     * Collect autoload file
     */
    private function collectAutoload()
    {
        Autoload::getInstance()->autoloadFile();
    }
    
    public function terminate()
    {
    }

}
