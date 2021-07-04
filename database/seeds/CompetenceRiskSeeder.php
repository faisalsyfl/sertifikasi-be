<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompetenceRiskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sectors = [
            [12,'Tinggi'],
            [14,'Menengah'],
            [16,'Menengah'],
            [17,'Menengah'],
            [18,'Menengah'],
            [19,'Menengah'],
            [22,'Menengah'],
            [28,'Menengah'],
            [29,'Rendah'],
            [33,'Menengah'],
            [34,'Menengah'],
            [35,'Rendah'],
            [36,'Rendah'],
        ];

        foreach ($sectors as $sector){
            \App\Models\Competence::where("Type","Sektor")
                ->where("code", $sector[0])
                ->update([
                    "risk" => $sector[1]
                ]);
        }
    }
}
