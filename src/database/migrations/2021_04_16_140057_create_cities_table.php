<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('cities', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->string('name');
        //     $table->bigInteger('state_id');
        //     $table->string('state_code');
        //     $table->bigInteger('country_id');
        //     $table->string('country_code');
        //     $table->decimal('latitude');
        //     $table->decimal('longitude');
        //     $table->tinyInteger('flag');
        //     $table->string('wikiDataId');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('cities');
    }
}