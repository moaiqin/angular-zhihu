<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test',function(Blueprint $table){
            //$table->unsignedInteger('id')->autoIncrement();这句等于
            $table->increments('id');
            $table->string('cat')->unllable();
            $table->text('article')->nullable()->comment('文章');//hou后umianshi数据表的注释
            $table->string('username',60)->unique();
        });
        Schema::rename('test','test_1');//就就是把表明改成test_1
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('test_1');
    }
}
