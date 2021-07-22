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
            $table->string('Atype')->nullable();
            $table->string('Bstatus')->nullable();
            $table->string('Cname')->nullable();
            $table->string('Ctype')->nullable();
            $table->string('Cwebsite')->nullable();
            $table->string('Cemail')->nullable();
            $table->string('Ctelp')->nullable();
            $table->string('Cmanagement')->nullable();
            $table->string('Cadministration')->nullable();
            $table->string('Cparttime')->nullable();
            $table->string('Cnonpermanent')->nullable();
            $table->integer('Cshift1')->nullable();
            $table->integer('Cshift2')->nullable();
            $table->integer('Cshift3')->nullable();
            $table->string('Coperationaltype')->nullable();
            $table->integer('Cpersonnel')->nullable();
            $table->string('Cmainaddress')->nullable();
            $table->string('Cmulti')->nullable();
            $table->string('Dscope')->nullable();
            $table->integer('Eq1')->nullable();
            $table->string('Ea1')->nullable();
            $table->integer('Eq2')->nullable();
            $table->string('Ea2')->nullable();
            $table->integer('Eq3')->nullable();
            $table->string('Ea3')->nullable();
            $table->integer('Eq4')->nullable();
            $table->string('Ea4')->nullable();
            $table->integer('Eq5')->nullable();
            $table->string('Ea5')->nullable();
            $table->integer('Eq6')->nullable();
            $table->string('Ea6')->nullable();
            $table->string('Eessay1')->nullable(); // peraturan perundang perundangan
            $table->string('Eessay2')->nullable();
            $table->string('Eessay3')->nullable();
            $table->string('F1')->nullable();
            $table->string('F2')->nullable();
            $table->string('F3')->nullable();
            $table->string('F4')->nullable();
            $table->string('F5')->nullable();
            $table->string('F6')->nullable();
            $table->string('F7')->nullable();
            $table->integer('agreement')->nullable();
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
