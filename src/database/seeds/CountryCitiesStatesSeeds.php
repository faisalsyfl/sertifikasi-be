<?php

use Illuminate\Database\Seeder;

class CountryCitiesStatesSeeds extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit',-1);
        $path = base_path('database/seeds/world.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}