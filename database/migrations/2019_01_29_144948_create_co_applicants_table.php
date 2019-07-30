<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_applicants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('application_id');
            $table->string('business_type');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('sin');
            $table->string('date_of_birth');
            $table->string('home_phone');
            $table->string('mobile_phone');
            $table->string('email_address');
            $table->string('street_name');
            $table->string('appt_po_box');
            $table->string('street_number');
            $table->string('company_city');
            $table->string('company_province');
            $table->string('company_postal_code');
            $table->string('relationship_to_applicant');
            $table->string('employer_address');
            $table->string('employer_phone');
            $table->string('number_of_years_employed');
            $table->string('number_of_months_employed');
            $table->string('gross_monthly_income');
            $table->string('position');
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
        Schema::dropIfExists('co_applicants');
    }
}
