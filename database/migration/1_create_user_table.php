<?php

use App\Main\QueryBuilder as DB;

class CreateUserTable
{
    public function __construct()
    {
        system("echo " . 'Migrating: CreateUserTable');
    }

    public function up()
    {
        system("echo " . 'Migrated: CreateUserTable');
    }
}
