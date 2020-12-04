<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class initialTaskTypeSeeder extends Seeder
{
    public $tableName = 'task_type';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->truncate();

        DB::table($this->tableName)->insert([
            'type' => 'url_redirect',
            'description' => 'url redirect',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'status' => 1
        ]);

        DB::table($this->tableName)->insert([
            'type' => 'info',
            'description' => 'info',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'status' => 1
        ]);

        DB::table($this->tableName)->insert([
            'type' => 'upload_document',
            'description' => 'upload document',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'status' => 1
        ]);
    }
}