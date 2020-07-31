<?php

namespace Main;

use Main\Autoload;
use Main\Routing\Route;

class Application
{
    /**
     * Instance of application routing
     * @var Route
     */
    private $route;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setRouter();
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
     * Register service providers
     * 
     * @return void
     */
    public function registerServiceProvider()
    {
        $providers = config('app.providers');

        if (!empty($providers)) {
            foreach ($providers as $provider) {
                $provider = new $provider;
                $provider->handle();
            }
        }
    }

    /**
     * Collect autoload file
     * 
     * @return void
     */
    private function prepareForRunning()
    {
        Autoload::getInstance()->autoloadFile();
        Autoload::getInstance()->checkAppKey(config('app.key'));
    }

    /**
     * Run the application
     */
    public function run()
    {
        $this->registerServiceProvider();
        $this->prepareForRunning();
    }

    /**
     * Terminate the application
     */
    public function terminate()
    { }
}
