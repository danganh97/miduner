<?php

use Main\Database\Migrations\Migration;

class CreateRoleTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (ColumnBuilder $table) {
            $table->increments('id')->comment('id of user');
            return $table->columns();
        });
    }

    public function down()
    {
        Schema::drop('roles');
    }
}
