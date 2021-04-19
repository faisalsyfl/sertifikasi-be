<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auditi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('organization_id');
            $table->string('name');
            $table->string('type');
            $table->string('website')->nullable();
            $table->string('email');
            $table->string('telp');
            $table->string('address');
            $table->integer('city_id')->nullable();
            $table->integer('province_id')->nullable();
            $table->integer('postcode')->nullable();
            $table->integer('stats')->default(1);
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
        Schema::dropIfExists('auditi');
    }
}
