<?php

use Main\Database\Migrations\Migration;
use Main\Database\DatabaseBuilder\Schema;
use Main\Database\DatabaseBuilder\ColumnBuilder;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (ColumnBuilder $table) {
            $table->increments('id')->comment('this is comment');
            $table->timestamps();
            return $table->columns();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
