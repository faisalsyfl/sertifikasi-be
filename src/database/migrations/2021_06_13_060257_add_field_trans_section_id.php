<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldTransSectionId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('form_location', 'transaction_id')) {
            Schema::table('form_location', function (Blueprint $table) {
                $table->integer('transaction_id')->nullable();
            });
        }

        if (!Schema::hasColumn('form_location', 'section_id')) {
            Schema::table('form_location', function (Blueprint $table) {
                $table->integer('section_id')->nullable();
            });
        }

        Schema::table('form_location', function (Blueprint $table) {
            $table->dropColumn('form_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('form_location', 'transaction_id')) {
            Schema::table('form_location', function ($table) {
                $table->dropColumn('transaction_id');
            });
        }

        if (Schema::hasColumn('form_location', 'section_id')) {
            Schema::table('form_location', function ($table) {
                $table->dropColumn('section_id');
            });
        }
    }
}