<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class initialProgramsSeeder extends Seeder
{
    public $tableName = 'programs';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->truncate();

        DB::table($this->tableName)->insert([
            'name' => 'Pre-Accomodating',
            'order' => 1,
            'status' => 1,
            'description' => 'Pre-Accomodating Program',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Accomodating',
            'order' => 2,
            'status' => 1,
            'description' => 'Accomodating Program',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Assimilating',
            'order' => 3,
            'status' => 1,
            'description' => 'Assimilating Program',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Accelerating',
            'order' => 4,
            'status' => 1,
            'description' => 'Accelerating Program',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}