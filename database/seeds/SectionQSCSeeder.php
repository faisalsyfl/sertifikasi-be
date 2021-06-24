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
        $this->step1();
    }

    private function step1(){
        $table = 'section_form';
        DB::table($table)->truncate();

        $organisasi = array();
        $organisasi[0] = ['npwp','required'];
        $organisasi[1] = ['nama','required'];
        $organisasi[2] = ['tipe','required'];
        $organisasi[3] = ['website',NULL];
        $organisasi[4] = ['email',NULL];
        $organisasi[5] = ['telp',NULL];
        $organisasi[6] = ['alamat',NULL];
        $organisasi[7] = ['negara',NULL];
        $organisasi[8] = ['provinsi',NULL];
        $organisasi[9] = ['kota',NULL];
        $organisasi[10] = ['kode_pos',NULL];
        foreach($organisasi as $o){
            DB::table($table)->insert([
                'section_id' => 1,
                'key' => $o[0],
                'rule' => $o[1],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

            ]);
        }

        $klien = array();
        $klien[1] = ['nama_klien','required'];
        $klien[2] = ['tipe_klien','required'];
        $klien[3] = ['website_klien',NULL];
        $klien[4] = ['email_klien',NULL];
        $klien[5] = ['telp_klien',NULL];
        $klien[6] = ['alamat_klien',NULL];
        $klien[7] = ['negara_klien',NULL];
        $klien[8] = ['provinsi_klien',NULL];
        $klien[9] = ['kota_klien',NULL];
        $klien[10] = ['kode_pos_klien',NULL];
        foreach($klien as $o){
            DB::table($table)->insert([
                'section_id' => 1,
                'key' => $o[0],
                'rule' => $o[1],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
        
    }
}
