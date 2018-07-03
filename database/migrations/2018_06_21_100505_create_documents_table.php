<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->comment = '文档表';
            $table->increments('id');
            $table->string('title', 64)->comment('文档标题');
            $table->tinyInteger('type')->default(1)->comment('文档类型。1接口文档，2普通文档');
            $table->string('url')->default('')->comment('接口地址');
            $table->tinyInteger('method')->default(0)->comment('请求方式');
            $table->tinyInteger('status')->default(0)->comment('开发状态');
            $table->string('version', 16)->default('1')->comment('版本');
            $table->text('arguments')->default('')->comment('参数及其说明json格式');
            $table->text('content')->default('')->comment('文档内容');
            $table->tinyInteger('sort')->default(99)->comment('排序');
            $table->unsignedInteger('created_by')->default(0)->comment('文档创建者');
            $table->unsignedInteger('updated_by')->default(0)->comment('文档修改者');
            $table->unsignedInteger('pro_id')->comment('所属项目id');
            $table->unsignedInteger('cat_id')->comment('所属目录id');
            $table->string('cat_ids')->default('')->comment('所属目录');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('cat_id');
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
        Schema::dropIfExists('documents');
    }
}
