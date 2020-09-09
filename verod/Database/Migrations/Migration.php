<?php

namespace Midun\Database\Migrations;

abstract class Migration
{
    protected $connection;

    public function __construct()
    {
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
