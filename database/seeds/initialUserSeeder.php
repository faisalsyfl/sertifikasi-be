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
        DB::table($this->tableName)->insert([
            'name' => 'user1',
            'nik' => 'user1',
            'username' => 'user1',
            'email' => 'user1@bjb',
            'phone' => '1234567890',
            'password' => Hash::make('user1'),
            'role' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'id_angkatan' => 1
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'user2',
            'nik' => 'user2',
            'username' => 'user2',
            'email' => 'user2@bjb',
            'phone' => '1234567890',
            'password' => Hash::make('user2'),
            'role' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'id_angkatan' => 1
        ]);
    }
}