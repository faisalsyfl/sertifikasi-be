<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(initialAdminSeeder::class);
        $this->call(initialAngkatanSeeder::class);
        $this->call(initialUserSeeder::class);
        $this->call(initialTaskTypeSeeder::class);
        $this->call(initialProgramsSeeder::class);
        $this->call(initialTaskSeeder::class);
        $this->call(dummyCommersMate::class);
    }
}