<?php

namespace Midun;

use Midun\Http\Exceptions\ErrorHandler;
use Midun\Http\Exceptions\RuntimeException;
use Midun\Traits\Instance;

class Application
{
    use Instance;

    /**
     * Container instance
     * 
     * @var \Midun\Container
     */
    private $container;

    /**
     * Instance of configuration
     * 
     * @var \Midun\Configuration\Config
     */
    private $config;


    /**
     * Flag check providers is loaded
     * 
     * @var bool
     */
    private $loaded = false;

    /**
     * Initial constructor
     * 
     * @param \Midun\Container $container
     * 
     * Set configuration instance
     * 
     * @return mixed
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->registerConfigProvider();

        new AliasLoader();

        register_shutdown_function([$this, 'whenShutDown']);

        $this->setErrorHandler();
    }

    /**
     * Register service providers
     * 
     * @return void
     */
    public function registerServiceProvider()
    {
        $providers = $this->container->make('config')->getConfig('app.providers');

        if (!empty($providers)) {
            foreach ($providers as $provider) {
                $provider = new $provider;
                $provider->register();
            }
            foreach ($providers as $provider) {
                $provider = new $provider;
                $provider->boot();
            }
        }
    }

    /**
     * Register initial configuration provider
     * 
     * @return void
     */
    private function registerConfigProvider()
    {
        $this->container->singleton('config', function () {
            return new \Midun\Configuration\Config();
        });
    }

    /**
     * Get status load provider
     * 
     * @return bool
     */
    public function isLoaded()
    {
        return $this->loaded;
    }

    /**
     * Set state load provider
     * 
     * @param bool $isLoad
     * 
     * @return bool
     */
    private function setLoadState(bool $isLoad)
    {
        $this->loaded = $isLoad;
    }

    /**
     * Load configuration
     * 
     * @return void
     */
    private function loadConfiguration()
    {
        $cache = array_filter(scandir(cache_path()), function ($item) {
            return strpos($item, '.php') !== false;
        });
        foreach ($cache as $item) {
            $key = str_replace('.php', '', $item);

            $value = require cache_path($item);

            $this->container->make('config')->setConfig($key, $value);
        }
    }

    /**
     * Run the application
     * 
     * @return void
     */
    public function run()
    {
        $this->loadConfiguration();
        $this->registerServiceProvider();
        $this->setLoadState(true);
    }

    /**
     * Terminate the application
     */
    public function terminate()
    { }

    /**
     * Set error handler
     * 
     * @return void
     */
    public function whenShutDown()
    {
        $last_error = error_get_last();
        if (!is_null($last_error)) {
            throw new RuntimeException($last_error['message']);
        }
    }

    /**
     * Set error handler
     */
    public function setErrorHandler()
    {
        set_error_handler(function () {
            $handler = new ErrorHandler;

            return $handler->errorHandler(...func_get_args());
        });
    }
}
