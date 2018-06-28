<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogs', function (Blueprint $table) {
            $table->comment = '文档所属目录表';
            $table->increments('id');
            $table->string('name', 64)->comment('目录名称');
            $table->unsignedInteger('parent_id')->default(0)->comment('上级目录');
            $table->string('parent_ids')->default('')->comment('上级目录');
            $table->tinyInteger('sort')->default(99)->comment('排序');
            $table->unsignedInteger('pro_id')->comment('所属项目id');
            $table->index('pro_id');
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
        Schema::dropIfExists('catalogs');
    }
}
