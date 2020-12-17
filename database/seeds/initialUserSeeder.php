<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class initialUserSeeder extends Seeder
{
    public $tableName = 'users';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table($this->tableName)->insert([
                'name' => 'commers' . $i,
                'nik' => 'commers' . $i,
                'username' => 'commers' . $i,
                'email' => 'commers' . $i . '@bjb',
                'phone' => '1234567890',
                'password' => Hash::make('commers'),
                'role' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'id_angkatan' => 1
            ]);
        }

        DB::table($this->tableName)->insert([
            'name' => 'mate1',
            'nik' => 'mate1',
            'username' => 'mate1',
            'email' => 'mate1@bjb',
            'phone' => '1234567890',
            'password' => Hash::make('mate'),
            'role' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'id_angkatan' => 1
        ], [
            'name' => 'mate2',
            'nik' => 'mate2',
            'username' => 'mate2',
            'email' => 'mate2@bjb',
            'phone' => '1234567890',
            'password' => Hash::make('mate'),
            'role' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'id_angkatan' => 1
        ]);
    }
}