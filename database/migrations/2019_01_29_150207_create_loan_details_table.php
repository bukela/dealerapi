<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('application_id');
            $table->string('down_payment');
            $table->string('trade-in_allowance');
            $table->string('payment_frequency');
            $table->string('payment_term');
            $table->string('amort_term');
            $table->string('defferal_periods');
            $table->string('amount_owing_at_the_end_of_loan_term');
            $table->string('rate');
            $table->string('contract_start_date');
            $table->string('first_payment_date');
            $table->string('payment');
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
        Schema::dropIfExists('loan_details');
    }
}
