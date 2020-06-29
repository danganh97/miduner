<?php

use Main\Colors;
use Main\Database\DatabaseBuilder\ColumnBuilder;
use Main\Database\DatabaseBuilder\Schema;
use Main\Database\Migrations\Migration;
use Main\QueryBuilder as DB;

class CreateUserTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (ColumnBuilder $table) {
            $table->increments('id')->comment('id of user');
            return $table->columns();
        });
        Schema::create('test', function (ColumnBuilder $table) {
            $table->increments('id')->comment('id of user');
            $table->text('content')->comment('content of user');
            $table->integer('user_id')->nullable()->default(3099);
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('company_id')->unsigned()->comment('id of company');
            $table->smallInteger('profile_id')->nullable()->default(1);
            $table->timestamps();
            return $table->columns();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('test');
    }
}
