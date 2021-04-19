<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_location', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('location_type');
            $table->string('address');
            $table->string('location');
            $table->integer('form_id');
            $table->integer('country_id');
            $table->integer('state_id');
            $table->integer('city_id');
            $table->integer('postcode');
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
        Schema::dropIfExists('form_location');
    }
}