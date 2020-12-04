<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class initialTaskSeeder extends Seeder
{
    public $tableName = 'task';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->truncate();
        DB::table($this->tableName)->insert([
            'name' => 'Cek Website BJB',
            'title' => 'Yuk Cek Website BJB !!',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.  Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ',
            'url' => 'https://www.bankbjb.co.id/ina',
            'image' => 'https://via.placeholder.com/200x100.png',
            'icon' => 'https://via.placeholder.com/64x64.png',
            'point' => 15,
            'order' => 1,
            'status' => 1,
            'id_task_type' => 1,
            'id_program' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'SK PEGAWAI',
            'title' => 'Cari tau informasi penandatangan SK Pegawai!',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.  Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ',
            'url' => '',
            'image' => 'https://via.placeholder.com/200x100.png',
            'icon' => 'https://via.placeholder.com/64x64.png',
            'point' => 15,
            'order' => 2,
            'status' => 1,
            'id_task_type' => 2,
            'id_program' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Cek Youtube BJB',
            'title' => 'Ayo cek youtube-nya bjb!',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.  Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ',
            'url' => 'https://youtube.com',
            'image' => 'https://via.placeholder.com/200x100.png',
            'icon' => 'https://via.placeholder.com/64x64.png',
            'point' => 15,
            'order' => 3,
            'status' => 1,
            'id_task_type' => 1,
            'id_program' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Perjanjian Kerja',
            'title' => 'Perjanjian Kerja',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.  Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ',
            'url' => '',
            'image' => 'https://via.placeholder.com/200x100.png',
            'icon' => 'https://via.placeholder.com/64x64.png',
            'point' => 15,
            'order' => 1,
            'status' => 1,
            'id_task_type' => 2,
            'id_program' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Perlengkapan',
            'title' => 'Perlengkapan',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.  Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ',
            'url' => '',
            'image' => 'https://via.placeholder.com/200x100.png',
            'icon' => 'https://via.placeholder.com/64x64.png',
            'point' => 15,
            'order' => 1,
            'status' => 1,
            'id_task_type' => 2,
            'id_program' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Penjadwalan',
            'title' => 'Penjadwalan',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.  Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ',
            'url' => '',
            'image' => 'https://via.placeholder.com/200x100.png',
            'icon' => 'https://via.placeholder.com/64x64.png',
            'point' => 15,
            'order' => 1,
            'status' => 1,
            'id_task_type' => 2,
            'id_program' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
