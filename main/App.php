<?php

require __DIR__ . '/Autoload.php';

class App
{
    private static $instance;

    private $storage;

    private $route;

    private function __construct($config)
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
        $this->config = $config;
        $this->route = $this->route;
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
    private function catchExceptionFatal()
    {
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error['type'] === E_ERROR) {
                echo $error['message'];
            }
        });
    }

    public static function getInstance($config = null)
    {
        if (!isset(self::$instance) && $config !== null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public function __set($name, $value)
    {
        $this->storage[$name] = !isset($this->storage[$name]) ? $value : $this->storage[$name];
    }

    public function __get($name)
    {
        return isset($this->storage[$name]) ? $this->storage[$name] : null;
    }

}
