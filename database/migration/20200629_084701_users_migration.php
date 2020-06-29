<?php

use Main\Database\Migrations\Migration;
use Main\Database\DatabaseBuilder\Schema;
use Main\Database\DatabaseBuilder\ColumnBuilder;

class CreateUsersTable extends Migration
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
            return $table->columns();
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
