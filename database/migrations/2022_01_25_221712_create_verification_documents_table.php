<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('verification_documents')) {
            // テーブルが存在していればリターン
            return;
        }
        Schema::create('verification_documents', function (Blueprint $table) {
            $table->id();
            $table->string('coach_id')->nullable()->comment('');
            $table->string('student_id')->nullable()->comment('');
            $table->string('document_type')->comment('');
            $table->string('img_front')->comment('');
            $table->string('img_back')->nullable()->comment('');
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
        Schema::dropIfExists('verification_documents');
    }
}
