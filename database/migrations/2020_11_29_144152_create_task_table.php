<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('title');
            $table->bigInteger('point');
            $table->text('icon');
            $table->text('description')->nullable();
            $table->text('url')->nullable();
            $table->text('image')->nullable();
            $table->text('document')->nullable();
            $table->integer('order');
            $table->integer('status');
            $table->integer('id_program');
            $table->integer('id_task_type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task');
    }
}