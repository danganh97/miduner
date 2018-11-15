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
        spl_autoload_register([$this, 'load']);
    }

    public function load($class)
    {
        $file = $this->root . '\\' . $class . '.php';
        if (file_exists($file)) {
            require $file;
        } else {
            throw new AppException("Class $class doesn't exists");
        }
    }

    private function autoloadFile()
    {
        foreach ($this->defaultFile() as $file) {
            require $this->root.'/'.$file;
        }
    }

    private function defaultFile()
    {
        return $this->autoload;
    }
}
