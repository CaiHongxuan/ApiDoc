<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->comment = '项目表';
            $table->increments('id');
            $table->string('name', 32)->comment('项目名称');
            $table->string('desc', 128)->default('')->comment('项目简介');
            $table->string('icon', 32)->default('')->comment('项目显示图片');
            $table->unsignedInteger('created_by')->default(0)->comment('创建者');
            $table->tinyInteger('sort')->default(99)->comment('排序');
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
        Schema::dropIfExists('projects');
    }
}
