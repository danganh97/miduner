<?php

use Midun\Database\DatabaseBuilder\Schema;
use Midun\Database\DatabaseBuilder\ColumnBuilder;

class CreateUserProfilesTable
{
    /** 
    * Run the migration.
    * @return void
    */
    public function up()
    {
        Schema::create('user_profiles', function (ColumnBuilder $table) {
            $table->increments('id')->comment('this is comment');
            $table->integer('user_id');
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
        Schema::dropIfExists('user_profiles');
    }
}
