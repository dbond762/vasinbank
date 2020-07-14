<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepositHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit_history', function (Blueprint $table) {
            $table->foreignId('deposit_id')->constrained('deposit');
            $table->bigInteger('amount_before')->unsigned();
            $table->set('entry', ['debit', 'credit'])->default('credit');
            $table->bigInteger('transaction_amount')->unsigned();
            $table->set('transaction_type', ['interest_accrual', 'commission_withdrawal'])->default('interest_accrual');
            $table->timestamps();
            $table->primary(['deposit_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deposit_history');
    }
}
