<?php

use Midun\Database\Migrations\Migration;
use Midun\Database\DatabaseBuilder\Schema;
use Midun\Database\DatabaseBuilder\ColumnBuilder;

class CreateUsersTable
{
    /** 
    * Run the migration.
    * @return void
    */
    public function up()
    {
        Schema::create('users', function (ColumnBuilder $table) {
            $table->increments('id')->comment('this is comment');
            $table->timestamps();
        });
    }

    /** 
    * Rollback the migration
    * @return void
    */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
