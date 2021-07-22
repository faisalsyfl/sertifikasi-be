<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auditor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('nip')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable(); //https://intranet.kemenperin.go.id/thumbnail.php?file=/files/sipegi/foto/090021872.jpg&max_width=150&max_height=150            $table->integer('stats')->default(1);
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
        Schema::dropIfExists('auditor');
    }
}
