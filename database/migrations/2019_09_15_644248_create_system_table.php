<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system', function (Blueprint $table) {
            $table->increments('id');
            $table->string('system_domain');//tableau域名
            $table->string('logo_url');//logo图片的路径
            $table->string('web_title');//网站标题
            $table->text('company');//所属公司
            $table->string('toolbar');//tablau报表的操作位置
            $table->enum('model',[1,2,3,4]);//用户默认访问的模式
            $table->enum('same_tableau',[1,2]);//是否使用
            $table->string('tableau_username');//tablau账户名
            $table->string('tableau_password');//tablau密码
            $table->string('toolbar');//tablau报表的操作位置
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system');
    }
}
