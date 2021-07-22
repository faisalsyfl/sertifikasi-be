<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('title');
            $table->string('code')->nullable();
            $table->string('type')->nullable(); //INTERNAL, EXTERNAL
            $table->string('file_hash')->nullable(); 
            $table->string('file_type')->nullable(); 
            $table->integer('file_size')->nullable(); 
            $table->string('created_by')->nullable(); 
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('document');
    }
}
