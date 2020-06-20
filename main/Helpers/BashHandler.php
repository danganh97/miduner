<?php

require_once './main/Helpers/common.php';
require_once './main/Helpers/execHelpers.php';

class BashHandler
{
    private $argv;

    public function __construct()
    {
        global $argv;
        $this->argv = $argv;
    }

    public function exec(): void
    {
        $this->_checkRequireAutoload();

        switch (strtolower($this->argv[1])) {
            case 'config:cache':
            case 'c:c':
            case 'c:f':
                execClearCache();
                execWriteCache();
                execWriteConfigCache();
                execWriteDataViews();
                break;
            case 'db:seed':
                execRunSeed();
                break;
            case 'migrate':
                execMigrate();
                break;
            case 'ser':
            case 'serve':
            case 'serv':
                execCreateServerCli($this->argv);
                break;
            case 'key:':
            case 'key:generate':
            case 'key:gen':
                execGenerateKey();
                break;
            default:
                # code...
                break;
        }
    }

    private function _checkRequireAutoload(): void
    {
        if (isset($this->argv[1])){
            if (
                strtolower($this->argv[1]) != 'config:cache'
                && strtolower($this->argv[1]) != 'c:f'
                && strtolower($this->argv[1]) != 'c:c'
                && strtolower($this->argv[1]) != 'key:'
            ) {
                require_once './main/Autoload.php';
                /**
                 * Miduner - A PHP Framework For Amateur
                 *
                 * @package  Miduner
                 * @author   Dang Anh <danganh.dev@gmail.com>
                 */
                $config = require_once './cache/app.php';
                new Autoload($config);

            }
        }
    }
}
