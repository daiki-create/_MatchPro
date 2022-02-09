<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempCoachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('temp_coaches')) {
            // テーブルが存在していればリターン
            return;
        }
        Schema::create('temp_coaches', function (Blueprint $table) {
            $table->id();
            $table->string('loginid')->unique()->comment('ログインID');
            $table->string('temp_passwd')->comment('仮パスワード');
            $table->string('temp_code')->comment('仮コード');
            $table->boolean('coach_flag')->comment('コーチフラグ');
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
        Schema::dropIfExists('temp_coaches');
    }
}
