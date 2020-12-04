<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class initialAdminSeeder extends Seeder
{
    public $tableName = 'users';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->truncate();

        DB::table($this->tableName)->insert([
            'name' => 'admin',
            'nik' => 'admin',
            'username' => 'admin',
            'email' => 'admin@admin',
            'phone' => '1234567890',
            'password' => Hash::make('adminXbjb'),
            'role' => 99,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}