<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancialInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('application_id');
            $table->string('credit_card_number');
            $table->string('credit_card_issuer');
            $table->string('declared_bankruptcy');
            $table->string('date_of_discharge');
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
        Schema::dropIfExists('financial_informations');
    }
}
