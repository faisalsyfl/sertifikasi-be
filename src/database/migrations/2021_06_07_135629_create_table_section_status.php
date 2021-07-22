<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSectionStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('section_id');
            $table->integer('transaction_id');
            /*
             * status:
             * 0 > empty
             * 1 > filled not complete
             * 2 > validated not complete
             * 3 > validated complete
             * */
            $table->integer('status');
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
        Schema::dropIfExists('section_status');
    }
}
