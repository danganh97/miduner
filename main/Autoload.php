<?php

namespace Main;

use Main\Services\Providers\EntityServiceProvider;

class Autoload
{
    /**
     * Root of application
     * @var string $root
     */
    private $root;

    /**
     * List of autoload files
     * @var array $autoload
     */
    public $autoload;

    /**
     * Type of running server
     * @var string $server
     */
    private $server;

    /**
     * List of aliases
     * @var array $aliases
     */
    private $aliases;

    /**
     * Instance application autoload
     * @var $instance
     */
    private static $instance;

    /**
     * Initial constructor autoload
     */
    public function __construct($config)
    {
        self::$instance = $this;
        $this->registerAutoload();
        $this->catchExceptionFatal();
        $this->setConfig($config);
        $this->checkAppKey($config['key']);
    }

    /**
     * Get instance of autoload
     */
    public static function getInstance()
    {
        if(!self::$instance) {
            die('App not loaded');
        }
        return self::$instance;
    }

    /**
     * Set local config
     * @param array $config
     *
     * @return void
     */
    private function setConfig($config)
    {
        $this->root = $config['base'];
        $this->autoload = $config['autoload'];
        $this->server = $config['server'];
        $this->aliases = $config['aliases'];
    }

    /**
     * Register function autoload
     *
     * @return void
     */
    private function registerAutoload()
    {
        spl_autoload_register([$this, 'load']);
    }

    /**
     * Autoload class
     * @param string $class
     *
     * @return void
     */
    public function load($class)
    {
        if (isset($this->aliases[$class])) {
            return $this->loadWithAlias($class);
        }
        switch ($this->server) {
            case 'linux':
            case 'macosx':
                $file = $this->getUnixPath($class);
                break;
            case 'windows':
                $file = $this->getWindowsPath($class);
                break;
        }
        $this->requireAfterCheckExists($file);
    }

    /**
     * Create the Unix operation path file
     * @param string $class
     *
     * @return string
     */
    private function getUnixPath(string $class)
    {
        return "$this->root/" . str_replace('\\', '/', lcfirst($class) . '.php');
    }

    /**
     * Create the Windows path file
     * @param string $class
     *
     * @return string
     */
    private function getWindowsPath(string $class)
    {
        return "{$this->root}\\{$class}.php";
    }

    /**
     * Autoload class
     *
     * @return void
     */
    public function autoloadFile()
    {
        try {
            foreach ($this->defaultFile() as $file) {
                $fullUrl = $this->getPathFromFile($file);
                $this->requireAfterCheckExists($fullUrl);
            }
        } catch (\Throwable $th) {
            toPre($th->getMessage());
        }
    }

    /**
     * Check exists file and require
     *
     * @param string $fullUrl
     *
     * @return \PDOInstance
     */
    private function requireAfterCheckExists(string $fullUrl)
    {
        if (file_exists($fullUrl)) {
            require_once $fullUrl;
        } else {
            throw new \Exception("File {$fullUrl} doesn't exists.");
        }
    }

    /**
     * Load with aliases
     * @param string $class
     * 
     * @return void
     */
    private function loadWithAlias($class)
    {
        return class_alias($this->aliases[$class], $class);
    }

    /**
     * Get full the path from file
     * @param string $file
     *
     * @return string
     */
    private function getPathFromFile(string $file)
    {
        return $this->root . '/' . $file;
    }

    /**
     * Checking app key
     *
     * @return void
     */
    private function checkAppKey(string $appkey)
    {
        if (empty($appkey) || $appkey == '' || $appkey == null) {
            die("Please generate app key.");
        }
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

    /**
     * Get the default file load
     *
     * @return array
     */
    private function defaultFile()
    {
        return $this->autoload;
    }
}
