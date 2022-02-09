<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('students')) {
            // テーブルが存在していればリターン
            return;
        }
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('loginid')->unique()->comment('ログインID');
            $table->string('passwd')->comment('パスワード');
            $table->string('name')->comment('氏名');
            $table->date('birth')->comment('生年月日');
            $table->string('icon')->comment('アイコンのファイル名');
            $table->enum('status', ['active','inactive','left','permanent'])->comment('ユーザステータス');
            $table->enum('identified_status', ['identified','unidentified','checking'])->comment('本人確認ステータス');
            $table->string('profile')->nullable()->comment('プロフィール・自己紹介');
            $table->string('payjp_customer_id')->nullable()->comment('payjp顧客ID');
            $table->string('payjp_card_id')->nullable()->comment('payjpカードID');
            $table->integer('is_force_logout')->default(0);
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
        Schema::dropIfExists('students');
    }
}
