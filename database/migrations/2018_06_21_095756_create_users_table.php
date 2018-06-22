<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->comment = '用户表';
            $table->increments('id');
            $table->string('name', 32)->comment('用户名称');
            $table->string('email', 64)->unique()->default('')->comment('邮箱');
            $table->string('phone', 16)->default('')->comment('手机');
            $table->string('password')->comment('密码');
            $table->string('remember_token');
            $table->tinyInteger('status')->default(1)->comment('是否启用。1是，0否');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
