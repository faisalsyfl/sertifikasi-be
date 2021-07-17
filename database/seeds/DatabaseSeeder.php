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
        $this->call(initialCompetenceSeeder::class);
        // $this->call(CountryCitiesStatesSeeds::class);
        $this->call(SectionQSCSeeder::class);
        $this->call(QSCSectionForm001Seeder::class);
        $this->call(QSCSectionForm002Seeder::class);
    }
}
