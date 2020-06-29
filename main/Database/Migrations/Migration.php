<?php

namespace Main\Database\Migrations;

use Main\Colors;
use Main\Database\DatabaseBuilder\ColumnBuilder;
use Main\Http\Exceptions\AppException;

abstract class Migration
{
    protected $connection;
    private $colors;

    public function __construct()
    {
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
