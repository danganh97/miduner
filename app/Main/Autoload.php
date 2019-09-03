<?php

use App\Http\Exceptions\Exception;
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
        switch($this->server) {
            case 'linux':
            $file = $this->root . '/' . str_replace('\\', '/', lcfirst($class) . '.php');
            case 'windows':
            $file = $this->root . '\\' . $class . '.php';
        }
        if (file_exists($file)) {
            require_once $file;
        } else {
            throw new Exception("Class $class doesn't exists");
        }
    }

    private function autoloadFile()
    {
        try {
            foreach ($this->defaultFile() as $file) {
                require_once $this->root . '/' . $file;
            }
        } catch (Exception $th) {
            toPre($this->root.'/'.$file);
        }

    }

    private function defaultFile()
    {
        return $this->autoload;
    }
}
