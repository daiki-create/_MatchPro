<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('messages')) {
            // テーブルが存在していればリターン
            return;
        }
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('sender_id')->comment('');
            $table->string('receiver_id')->comment('');
            $table->string('coach_id')->comment('');
            $table->string('student_id')->comment('');
            $table->enum('sender',['coach','student','system'])->comment('');
            $table->string('message')->comment('');
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
        Schema::dropIfExists('messages');
    }
}
