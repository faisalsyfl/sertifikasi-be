<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    // id int [pk, increment]
    // name varchar
    // npwp varchar
    // type varchar //Swasta, Pemerintah, BUMN, BUMD
    // website varchar
    // email varchar
    // telp varchar
    // alamat varchar
    // city_id int
    // province_id int
    // country_id int
    // postcode int
    public function up()
    {
        Schema::create('organization', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('npwp');
            $table->string('type');
            $table->string('website');
            $table->string('email');
            $table->string('telp');
            $table->string('address');
            $table->integer('city_id');
            $table->integer('province_id');
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
        Schema::dropIfExists('organization');
    }
}
