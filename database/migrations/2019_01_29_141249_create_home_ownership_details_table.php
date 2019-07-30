<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeOwnershipDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_ownership_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('application_id');
            $table->string('residential_status');
            $table->string('title_of_property');
            $table->string('monthly_payment');
            $table->string('payment_made_to');
            $table->string('outstanding_mortgage_balance');
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
        Schema::dropIfExists('home_ownership_details');
    }
}
