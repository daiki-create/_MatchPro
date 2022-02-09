<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('reservations')) {
            // テーブルが存在していればリターン
            return;
        }
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('coach_id')->comment('');
            $table->string('student_id')->comment('');
            $table->date('date')->comment('');
            $table->time('start_time')->comment('');
            $table->time('end_time')->comment('');
            $table->string('fee')->comment('');
            $table->string('num')->comment('');
            $table->string('content')->comment('');
            $table->enum('status', ['temp','accepted','rejected','canceled'])->comment('');
            $table->string('review')->nullable()->comment('');
            $table->enum('answered_flag', ['unanswered','answered','passed'])->comment('');
            $table->string('charged_flag')->nullable()->comment('');
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
        Schema::dropIfExists('reservations');
    }
}
