<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmploymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('application_id');
            $table->string('employment_status');
            $table->string('company_name');
            $table->string('company_address');
            $table->string('company_city');
            $table->string('company_province');
            $table->string('company_postal_code');
            $table->string('company_telephone');
            $table->string('company_type_of_business');
            $table->string('company_salary');
            $table->string('company_salary_type');
            $table->string('company_months_of_employment');
            $table->string('company_years_of_employment');
            $table->string('company_position');
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
        Schema::dropIfExists('employments');
    }
}
