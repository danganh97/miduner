<?php

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
            $table->string('name');
            $table->string('email');
            $table->date('dob');
            $table->string('password');
            $table->integer('is_active')->comment('0 is hidden, 1 is active');
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
