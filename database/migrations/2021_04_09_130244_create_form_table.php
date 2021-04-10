<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('transaction_id');
            $table->string('A1')->nullable();
            $table->string('B1')->nullable();
            $table->string('C1')->nullable();
            $table->string('C2')->nullable();
            $table->string('C3')->nullable();
            $table->string('C4')->nullable();
            $table->string('C5')->nullable();
            $table->string('C6')->nullable();
            $table->string('C7')->nullable();
            $table->string('C8')->nullable();
            $table->string('C9')->nullable();
            $table->integer('C10')->nullable();
            $table->integer('C11')->nullable();
            $table->integer('C12')->nullable();
            $table->string('C13')->nullable();
            $table->integer('C14')->nullable();
            $table->string('C15')->nullable();
            $table->string('C16')->nullable();
            $table->string('D1')->nullable();
            $table->integer('E1')->nullable();
            $table->string('E2')->nullable();
            $table->integer('E3')->nullable();
            $table->string('E4')->nullable();
            $table->integer('E5')->nullable();
            $table->string('E6')->nullable();
            $table->integer('E7')->nullable();
            $table->string('E8')->nullable();
            $table->integer('E9')->nullable();
            $table->string('E10')->nullable();
            $table->integer('E11')->nullable();
            $table->string('E12')->nullable();
            $table->string('E13')->nullable(); // peraturan perundang perundangan
            $table->string('E14')->nullable();
            $table->string('E15')->nullable();
            $table->string('F1')->nullable();
            $table->string('F2')->nullable();
            $table->string('F3')->nullable();
            $table->string('F4')->nullable();
            $table->string('F5')->nullable();
            $table->string('F6')->nullable();
            $table->string('F7')->nullable();
            $table->integer('X')->nullable();
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
        Schema::dropIfExists('form');
    }
}
