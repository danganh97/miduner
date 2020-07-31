<?php

require_once './main/Helpers/common.php';
require_once './main/Helpers/execHelpers.php';
require_once './main/Colors.php';
require_once './main/Http/Exceptions/AppException.php';

class BashHandler
{
    private $argv;
    private $colors;

    public function __construct()
    {
        global $argv;
        $this->argv = $argv;
        $this->colors = new \Main\Colors;
    }

    private function _checkRequireAutoload()
    {
        if (isset($this->argv[1])) {
            if (
                strtolower($this->argv[1]) != 'config:cache'
                && strtolower($this->argv[1]) != 'c:f'
                && strtolower($this->argv[1]) != 'c:c'
                && strtolower($this->argv[1]) != 'key:'
            ) {
                require_once './main/Autoload.php';
                require_once './main/Application.php';
                /**
                 * Miduner - A PHP Framework For Amateur
                 *
                 * @package  Miduner
                 * @author   Dang Anh <danganh.dev@gmail.com>
                 */
                $config = require_once './cache/app.php';
                new Main\Autoload($config);
                new Main\Application($config);
            }
        }
    }
}
