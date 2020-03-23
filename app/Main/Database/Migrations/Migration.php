<?php

namespace App\Main\Database\Migrations;

use App\Main\Colors;
use App\Main\Http\Exceptions\AppException;

abstract class Migration
{
    protected $connection;
    private $colors;

    public function __construct()
    {
        $this->colors = new Colors;
        if (method_exists($this, 'up')) {
            return call_user_func([$this, 'up']);
        }
        $message = "Method 'up' doesn't exists in " . get_called_class();
        if(config('app.server') == 'windows') {
            system("echo $message");exit;
        }
        echo $this->colors->printError($message);
        exit;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
