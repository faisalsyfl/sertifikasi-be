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
            ],
            [
                'section_id' => 3,
                'key' => 'scope',
                'rule' => null,
            ],
            [
                'section_id' => 3,
                'key' => 'jumlah_personil_efektif',
                'rule' => null,
            ],
            [
                'section_id' => 3,
                'key' => 'audit_tahap_1',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'jumlah_auditor_tahap_1',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'start_jadwal_tahap_1',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'end_jadwal_tahap_1',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'type_tahap_1',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'auditor_ids_tahap_1',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'jumlah_auditor_tahap_2',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'start_jadwal_tahap_2',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'end_jadwal_tahap_2',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'type_tahap_2',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'auditor_ids_tahap_2',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'jumlah_auditor_survailen_tahap_1',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'start_jadwal_survailen_tahap_1',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'end_jadwal_survailen_tahap_1',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'type_survailen_tahap_1',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'auditor_ids_survailen_tahap_1',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'jumlah_auditor_survailen_tahap_2',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'start_jadwal_survailen_tahap_2',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'end_jadwal_survailen_tahap_2',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'type_survailen_tahap_2',
                'rule' => null,
            ],
            [
                'section_id' => 5,
                'key' => 'auditor_ids_survailen_tahap_2',
                'rule' => null,
            ],
            [
                'section_id' => 6,
                'key' => 'nama_klien',
                'rule' => null,
            ],
            [
                'section_id' => 6,
                'key' => 'alamat_klien',
                'rule' => null,
            ],
            [
                'section_id' => 6,
                'key' => 'status_aplikasi_sertifikasi', // kriteria_audit
                'rule' => null,
            ],
            [
                'section_id' => 3,
                'key' => 'lingkup',
                'rule' => null,
            ],
            [
                'section_id' => 6,
                'key' => 'lingkup',
                'rule' => null,
            ],
            [
                'section_id' => 6,
                'key' => 'scope',
                'rule' => null,
            ],
            [
                'section_id' => 6,
                'key' => 'sektor_ea',
                'rule' => null,
            ],
            [
                'section_id' => 6,
                'key' => 'sektor_nace',
                'rule' => null,
            ],
            [
                'section_id' => 6,
                'key' => 'nomor_sertifikasi',
                'rule' => null,
            ],
            [
                'section_id' => 6,
                'key' => 'evaluasi_file',
                'rule' => null,
            ],
            [
                'section_id' => 7,
                'key' => 'nomor_sertifikat',
                'rule' => null,
            ],
            [
                'section_id' => 7,
                'key' => 'start_sertifikat',
                'rule' => null,
            ],
            [
                'section_id' => 7,
                'key' => 'end_sertifikat',
                'rule' => null,
            ],
            [
                'section_id' => 7,
                'key' => 'draft_sertifikat',
                'rule' => null,
            ],
            [
                'section_id' => 7,
                'key' => 'published_sertifikat',
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
