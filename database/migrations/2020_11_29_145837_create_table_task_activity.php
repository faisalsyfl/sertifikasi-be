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
            $table->text('review')->nullable();
            $table->integer('status')->nullable();
            $table->integer('id_task');
            $table->integer('id_user');
            $table->integer('id_comment')->nullable();
            $table->integer('id_like')->nullable();
            $table->integer('id_hashtag')->nullable();
            $table->integer('id_attachment')->nullable();
            $table->integer('id_angkatan');
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