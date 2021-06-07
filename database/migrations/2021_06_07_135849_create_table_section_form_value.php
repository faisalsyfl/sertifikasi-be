<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSectionFormValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_form_value', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('section_status_id');
            $table->integer('section_form_id');
            $table->string('reference_table', 50)->default(null)->nullable();
            $table->integer('reference_id')->default(null)->nullable();
            $table->text('value')->nullable();
            $table->text('frozen_value')->default(null)->nullable();
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
        Schema::dropIfExists('section_form_value');
    }
}
