<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class initialAngkatanSeeder extends Seeder
{
    public $tableName = 'angkatan';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->truncate();

        DB::table($this->tableName)->insert([
            'name' => 'angkatan 2021',
            'description' => 'angkatan 2021 BJB',
            'tahun' => 2021,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}