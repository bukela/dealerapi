<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationGeneralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_generals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('application_id');
            $table->string('amount');
            $table->string('user_id');
            $table->string('title');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('sin');
            $table->string('martial_status');
            $table->string('dependents');
            $table->date('date_of_birth');
            $table->string('home_phone');
            $table->string('mobile_phone');
            $table->string('email_address');
            $table->string('prefered_language');
            $table->string('type_of_government_id');
            $table->string('government_id_provided');
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code');
            $table->string('duration_at_current_location');
            $table->string('filled');
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
        Schema::dropIfExists('application_generals');
    }
}
