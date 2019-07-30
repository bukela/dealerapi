<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('application_id');
            $table->string('program');
            $table->string('class');
            $table->string('type');
            $table->string('description');
            $table->string('vehicle_year');
            $table->string('vehicle_odometer');
            $table->string('serial_number');
            $table->decimal('equipment_cost', 10,2);
            $table->string('merchant');
            $table->string('merchant_rep');
            $table->string('data_entry');
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
        Schema::dropIfExists('equipment_types');
    }
}
