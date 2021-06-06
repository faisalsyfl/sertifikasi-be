<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class initialCompetenceSeeder extends Seeder
{
    public $tableName = 'competence';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->truncate();

        $sistems = [
          'Mutu',
          'Lingkungan',
          'Produk',
          'Keselamatan',
          'Industri Hijau'
        ];

        $sektors = [
          'Sektor 12',
          'Sektor 14',
          'Sektor 16',
          'Sektor 17',
          'Sektor 18',
          'Sektor 19',
          'Sektor 22',
          'Sektor 23',
          'Sektor 29',
          'Sektor 33',
          'Sektor 35',
          'Sektor 36',
        ];
        for($i=0;$i<count($sistems);$i++){
          DB::table($this->tableName)->insert([
              'name' => $sistems[$i],
              'type' => 'Sistem',
              'code' => $sistems[$i] == "Industri Hijau" ? "IH" : substr($sistems[$i],0,1),
              'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ]);
        }
        for($i=0;$i<count($sektors);$i++){
          DB::table($this->tableName)->insert([
              'name' => $sektors[$i],
              'type' => 'Sektor',
              'code' => substr($sektors[$i],-2),
              'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ]);
        }
    }
}
