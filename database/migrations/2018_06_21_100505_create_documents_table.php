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
            $table->comment = '文章表';
            $table->increments('id');
            $table->string('title', 64)->comment('文章标题');
            $table->string('url')->default('')->comment('接口地址');
            $table->tinyInteger('method')->default(0)->comment('请求方式');
            $table->text('arguments')->default('')->comment('参数及其说明json格式');
            $table->text('content')->default('')->comment('文章内容');
            $table->unsignedInteger('created_by')->default(0)->comment('文章创建者');
            $table->unsignedInteger('updated_by')->default(0)->comment('文章修改者');
            $table->unsignedInteger('cat_id')->comment('所属目录id');
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
