<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreviousEmploymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('previous_employments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('application_id');
            $table->string('previous_company_name');
            $table->string('previous_company_address');
            $table->string('previous_company_city');
            $table->string('previous_company_province');
            $table->string('previous_company_postal_code');
            $table->string('previous_company_telephone');
            $table->string('previous_company_years_of_employment');
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
        Schema::dropIfExists('previous_employments');
    }
}
