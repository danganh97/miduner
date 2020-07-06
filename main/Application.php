<?php

namespace Main;

use Main\Autoload;
use Main\Routing\Route;

class Application
{
    private $route;

    public function __construct($config)
    {
        $this->setRouter();
        $this->registerGlobalConfig($config);
        $this->registerServiceProvider($config['providers']);
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

    /**
     * Register global config
     *
     * @return void
     */
    private function registerGlobalConfig($config)
    {
        app()->singleton('config', $config);
        app()->singleton('route', $this->route);
    }

    private function registerServiceProvider($providers = [])
    {
        foreach ($providers as $provider) {
            $provider = new $provider;
            $provider->handle();
        }
    }

    /**
     * Run the Application
     *
     * @return mixed
     */
    public function run()
    {
        return $this->route->run();
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
