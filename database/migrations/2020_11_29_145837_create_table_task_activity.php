<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTaskActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_activity', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('point');
            $table->integer('status');
            $table->integer('like');
            $table->integer('id_task')->nullable(true);
            $table->integer('id_user')->nullable(true);
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
        Schema::dropIfExists('task_activity');
    }
}