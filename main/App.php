<?php

require __DIR__ . '/Autoload.php';

use Main\Http\Exceptions\AppException;
use Main\Registry;

class App
{
    private $route;

    public function __construct($config)
    {
        $this->registerClassAutoload($config);
        $this->registerGlobalConfig($config);
        $this->catchExceptionFatal();
        $this->setRouter();
    }

    /**
     * Set router
     *
     * @return void
     */
    public function setRouter()
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
        Registry::getInstance()->config = $config;
        Registry::getInstance()->route = $this->route;
    }

    /**
     *  Register class autoload
     *
     * @return void
     */
    private function registerClassAutoload($config)
    {
        new Autoload($config);
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
     * Catcher exception fatal
     *
     * @return \PDOInstance
     */
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
