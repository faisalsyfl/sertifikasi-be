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
            $table->text('review');
            $table->integer('status')->nullable();
            $table->text('document')->nullable();
            $table->text('image')->nullable();
            $table->text('video')->nullable();
            $table->integer('id_task');
            $table->integer('id_user');
            $table->integer('id_comment');
            $table->integer('id_like');
            $table->integer('id_hashtag');
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