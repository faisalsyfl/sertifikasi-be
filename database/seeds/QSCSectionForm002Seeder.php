<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class QSCSectionForm002Seeder extends Seeder
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
                'section_id' => 4,
                'key' => 'penawaran',
                'rule' => null,
            ],
            [
                'section_id' => 4,
                'key' => 'biaya_sertifikasi',
                'rule' => 'required',
            ],
            [
                'section_id' => 4,
                'key' => 'transportasi',
                'rule' => 'required',
            ],
            [
                'section_id' => 4,
                'key' => 'terbilang',
                'rule' => null,
            ],
            [
                'section_id' => 4,
                'key' => 'total',
                'rule' => null,
            ],
            [
                'section_id' => 4,
                'key' => 'nama_klien',
                'rule' => null,
            ],
            [
                'section_id' => 4,
                'key' => 'nomor_sertifikasi',
                'rule' => null,
            ],
            [
                'section_id' => 4,
                'key' => 'nomor_registrasi',
                'rule' => null,
            ],
            [
                'section_id' => 4,
                'key' => 'payment',
                'rule' => null,
            ]
        ];

        foreach ($section_forms as $section_form){
            $record = \App\Models\SectionForm::where("section_id", $section_form['section_id'])
                ->where("key", $section_form['key'])->first();
            if(!$record){
                DB::table($this->tableName)->insert([
                    [
                        'section_id' => $section_form['section_id'],
                        'key' => $section_form['key'],
                        'rule' => $section_form['rule'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    ]
                ]);
            }else{
                $record->update([
                    'rule' => $section_form['rule'],
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
