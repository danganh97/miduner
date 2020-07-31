<?php

namespace Main;

use Main\Http\Exceptions\AppException;

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
     * Instance application autoload
     * @var $instance
     */
    private static $instance;

    /**
     * Initial constructor autoload
     */
    public function __construct()
    {
        self::$instance = $this;
        $this->registerAutoload();
        $this->catchExceptionFatal();
        $this->setConfig();
    }

    /**
     * Get instance of autoload
     * 
     * @return self
     */
    public static function getInstance()
    {
        if (!self::$instance) {
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
    private function setConfig()
    {
        $this->root = BASE;
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
    public function load(string $class)
    {
        $isAliases = $this->isAliases($class);

        if (!$isAliases) {
            switch ($this->getOS()) {
                case 'linux':
                case 'macosx':
                    $file = $this->getUnixPath($class);
                    break;
                case 'windows':
                    $file = $this->getWindowsPath($class);
                    break;
                default:
                    die('Unsupported OS');
            }
            $this->requireAfterCheckExists($file);
        }
        
    }

    /**
     * Check class loading is aliases
     * 
     * @param string $class
     * 
     * @return boolean
     */
    public function isAliases(string $class)
    {
        $cacheApp = BASE . '/cache/app.php';
        
        if(file_exists($cacheApp)) {
            $configs = include $cacheApp;
            if (isset($configs['aliases'][ucfirst($class)])) {
                return $this->loadWithAlias($configs['aliases'], ucfirst($class));
            }
    
        }
        
        return false;
    }

    /**
     * Get OS specific
     * 
     * @return string
     */
    public function getOS()
    {
        switch (true) {
            case stristr(PHP_OS, 'DAR'):
                return 'macosx';
            case stristr(PHP_OS, 'WIN'):
                return 'windows';
            case stristr(PHP_OS, 'LINUX'):
                return 'linux';
            default:
                return 'unknown';
        }
    }

    /**
     * Create the Unix operation path file
     * @param string $class
     *
     * @return string
     */
    private function getUnixPath(string $class)
    {
        return "{$this->root}/" . str_replace('\\', '/', lcfirst($class) . '.php');
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
            throw new AppException("File {$fullUrl} doesn't exists.");
        }
    }

    /**
     * Load with aliases
     * @param string $class
     *
     * @return void
     */
    private function loadWithAlias($aliases, $class)
    {
        return class_alias($aliases[$class], $class);
    }

    /**
     * Get full the path from file
     * @param string $file
     *
     * @return string
     */
    private function getPathFromFile(string $file)
    {
        return "{$this->root}/$file";
    }

    /**
     * Checking app key
     *
     * @return void
     */
    public function checkAppKey(string $appkey)
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
                throw new AppException($error['message']);
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
        return config('app.autoload');
    }
}
