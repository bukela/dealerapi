<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreviousAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('previous_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('application_id');
            $table->string('address');
            $table->string('address_2');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code');
            $table->string('duration_at_current_location');
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
        Schema::dropIfExists('previous_addresses');
    }
}
