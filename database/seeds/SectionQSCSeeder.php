<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SectionQSCSeeder extends Seeder
{
    public $tableName = 'section';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->truncate();
        DB::table($this->tableName)->insert([
            [
                'category' => 'QSC',
                'order' => 1,
                'name' => 'Pendaftaran',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'category' => 'QSC',
                'order' => 2,
                'name' => 'Aplikasi',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'category' => 'QSC',
                'order' => 3,
                'name' => 'Kajian Aplikasi',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'category' => 'QSC',
                'order' => 4,
                'name' => 'Biaya',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'category' => 'QSC',
                'order' => 5,
                'name' => 'Audit',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'category' => 'QSC',
                'order' => 6,
                'name' => 'Evaluasi',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'category' => 'QSC',
                'order' => 7,
                'name' => 'Sertifikat',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
