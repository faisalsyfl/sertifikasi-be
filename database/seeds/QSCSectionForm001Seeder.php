<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class QSCSectionForm001Seeder extends Seeder
{
    public $tableName = 'section_form';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $section_forms = [
            [
                'section_id' => 1,
                'key' => 'auditi_status',
                'rule' => null,
            ],
            [
                'section_id' => 2,
                'key' => 'nama_klien',
                'rule' => null,
            ],
            [
                'section_id' => 2,
                'key' => 'tipe_klien',
                'rule' => null,
            ],
            [
                'section_id' => 2,
                'key' => 'website_klien',
                'rule' => null,
            ],
            [
                'section_id' => 2,
                'key' => 'email_klien',
                'rule' => null,
            ],
            [
                'section_id' => 2,
                'key' => 'telp_klien',
                'rule' => null,
            ],
            [
                'section_id' => 3,
                'key' => 'catatan_aplikasi_ditolak',
                'rule' => null,
            ],
            [
                'section_id' => 3,
                'key' => 'alamat_klien',
                'rule' => null,
            ]
        ];

        foreach ($section_forms as $section_form){
            if(
                \App\Models\SectionForm::where("section_id", $section_form['section_id'])
                    ->where("key", $section_form['key'])->count() == 0
            ){
                DB::table($this->tableName)->insert([
                    [
                        'section_id' => $section_form['section_id'],
                        'key' => $section_form['key'],
                        'rule' => $section_form['rule'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    ]
                ]);
            }
        }
    }
}
