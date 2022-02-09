<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('payroll_accounts')) {
            // テーブルが存在していればリターン
            return;
        }
        Schema::create('payroll_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('coach_id')->comment('');
            $table->string('bank')->comment('');
            $table->string('bank_code')->nullable()->comment('');
            $table->string('branch')->comment('');
            $table->string('branch_code')->nullable()->comment('');
            $table->enum('account_type', ['savings','current','super_savings'])->comment('');
            $table->string('symbol_number')->comment('');
            $table->string('name')->comment('');
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
        Schema::dropIfExists('payroll_accounts');
    }
}
