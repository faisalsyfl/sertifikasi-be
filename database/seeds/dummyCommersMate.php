<?php

use Illuminate\Database\Seeder;

class dummyCommersMate extends Seeder
{
    public $tableName = 'commers_has_mate';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table($this->tableName)->insert([
                'id_commers' => $i,
                'id_mate' => 12,
            ]);
        }
    }
}