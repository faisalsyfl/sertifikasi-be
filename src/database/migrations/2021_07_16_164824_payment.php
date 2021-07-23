<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Payment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('transaction_id');
            $table->string('type')->default('penawaran');
            $table->bigInteger('amount')->default(0);
            $table->string('method')->default('VA');
            $table->string('payment_code')->nullable()->default(null);
            $table->string('status')->default(0);
            $table->text('invoice')->nullable()->default(null);
            $table->text('receipt')->nullable()->default(null);
            $table->text('other_documents')->nullable()->default(null);
            $table->timestamp('payment_datetime')->nullable()->default(null);
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
        Schema::dropIfExists('payment');
    }
}
