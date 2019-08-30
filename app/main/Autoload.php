<?php

use App\Main\AppException;

class Autoload
{
    private $root;

    public function __construct($config)
    {
        $this->root = $config['appurl'];
        $this->autoload = $config['autoload'];
        $this->autoloadFile();
        $this->server = $config['server'];
        spl_autoload_register([$this, 'load']);
    }

    public function load($class)
    {
        $tmp = explode('\\', $class);
        $className = end($tmp);
        $pathName = str_replace($className, '', $class);
        $file = strtolower($this->server) == 'linux'
        ? $this->root . '/' . str_replace('\\', '/', strtolower($pathName)) . $className . '.php'
        : $this->root . '\\' . $pathName . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
        } else {
            throw new AppException("Class $class doesn't exists");
        }
    }

    private function autoloadFile()
    {
        try {
            foreach ($this->defaultFile() as $file) {
                require_once $this->root . '/' . $file;
            }
        } catch (\Throwable $th) {
        }

    }

    private function defaultFile()
    {
        return $this->autoload;
    }
}
