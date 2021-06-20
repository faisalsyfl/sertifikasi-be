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

        $table = 'section_form';
        DB::table($table)->truncate();

        $this->step1($table);
        $this->step2($table);
        $this->step3($table);
    }

    private function step1($table){
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

    private function step2($table){
        $forms = [
            [
                "key" => "manajemen_mutu",
                "rule" => ""
            ],
            [
                "key" => "manajemen_lingkungan",
                "rule" => ""
            ],
            [
                "key" => "manajemen_keselamatan",
                "rule" => ""
            ],
            [
                "key" => "industri_hijau",
                "rule" => ""
            ],
            [
                "key" => "status_aplikasi_sertifikasi",
                "rule" => "required"
            ],
            [
                "key" => "auditi_id",
                "rule" => "required|exists:auditi,id"
            ],
            [
                "key" => "jumlah_personil_management",
                "rule" => ""
            ],
            [
                "key" => "jumlah_personil_administrasi",
                "rule" => ""
            ],
            [
                "key" => "jumlah_personil_part_time",
                "rule" => ""
            ],
            [
                "key" => "jumlah_personil_non_permanen",
                "rule" => ""
            ],
            [
                "key" => "jumlah_personil_shift_1",
                "rule" => ""
            ],
            [
                "key" => "jumlah_personil_shift_2",
                "rule" => ""
            ],
            [
                "key" => "jumlah_personil_shift_3",
                "rule" => ""
            ],
            [
                "key" => "jumlah_personil_efektif",
                "rule" => ""
            ],
            [
                "key" => "alamat_id",
                "rule" => ""
            ],
            [
                "key" => "ruang_lingkup",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_1",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_2",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_3",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_4",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_5",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_6",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_7",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_8",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_9",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_1_keterangan",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_2_keterangan",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_3_keterangan",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_4_keterangan",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_5_keterangan",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_6_keterangan",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_7_keterangan",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_8_keterangan",
                "rule" => ""
            ],
            [
                "key" => "informasi_tambahan_9_keterangan",
                "rule" => ""
            ],
            [
                "key" => "tanda_tangan_formulir",
                "rule" => ""
            ]
        ];

        foreach($forms as $item){
            DB::table($table)->insert([
                'section_id' => 2,
                'key' => $item["key"],
                'rule' => $item["rule"] ? $item["rule"] : null,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }

    private function step3($table){
        $forms = [
            // Nama auditi
            [
                "key" => "nama_klien",
                "rule" => "required"
            ],
            // Status Aplikasi
            [
                "key" => "status_aplikasi_sertifikasi",
                "rule" => "required"
            ],
            // Kriteria Audit
            [
                "key" => "manajemen_mutu",
                "rule" => ""
            ],
            [
                "key" => "manajemen_lingkungan",
                "rule" => ""
            ],
            [
                "key" => "manajemen_keselamatan",
                "rule" => ""
            ],
            [
                "key" => "industri_hijau",
                "rule" => ""
            ],
            // Perlakuan Audit
            [
                "key" => "audit_single",
                "rule" => ""
            ],
            [
                "key" => "audit_joint",
                "rule" => ""
            ],
            [
                "key" => "audit_combination",
                "rule" => ""
            ],
            [
                "key" => "audit_integration",
                "rule" => ""
            ],
            // Lingkup Sertifikasi
            [
                "key" => "lingkup",
                "rule" => ""
            ],
            [
                "key" => "sektor_ea",
                "rule" => ""
            ],
            [
                "key" => "sektor_nace",
                "rule" => ""
            ],
            [
                "key" => "audit_integration",
                "rule" => ""
            ],
            [
                "key" => "akreditasi_lingkup_kan",
                "rule" => ""
            ],
            // Kompetensi Personil
            [
                "key" => "personil_kompeten",
                "rule" => ""
            ],
            // Ketidakberpihakan
            [
                "key" => "konflik_kepentingan",
                "rule" => ""
            ],
            [
                "key" => "penjelasan_solusi",
                "rule" => ""
            ],
            // Lokasi Organisasi Pemohon
            [
                "key" => "lokasi_id",
                "rule" => "exists:form_location,id"
            ],
            [
                "key" => "multi_site",
                "rule" => ""
            ],
            // Shift kerja yang diaudit
            [
                "key" => "audit_shift_1",
                "rule" => ""
            ],
            [
                "key" => "audit_shift_2",
                "rule" => ""
            ],
            [
                "key" => "audit_shift_3",
                "rule" => ""
            ],
            // Risiko ISO 9001:2015
            [
                "key" => "risiko_iso_9001",
                "rule" => ""
            ],
            // Kompleksitas ISO 14001:2015
            [
                "key" => "kompleksitas_iso_14001",
                "rule" => ""
            ],
            // Kompleksitas ISO 45001:2018
            [
                "key" => "kompleksitas_iso_45001",
                "rule" => ""
            ],
            // Perhitungan Waktu Audit
            [
                "key" => "jumlah_personil_management",
                "rule" => ""
            ],
            [
                "key" => "jumlah_personil_administrasi",
                "rule" => ""
            ],
            [
                "key" => "jumlah_personil_part_time",
                "rule" => ""
            ],
            [
                "key" => "jumlah_personil_non_permanen",
                "rule" => ""
            ],
            [
                "key" => "jumlah_personil_shift_1",
                "rule" => ""
            ],
            [
                "key" => "jumlah_personil_shift_2",
                "rule" => ""
            ],
            [
                "key" => "jumlah_personil_shift_3",
                "rule" => ""
            ],
            [
                "key" => "keterangan_shift",
                "rule" => ""
            ],
            [
                "key" => "waktu_audit_iso_9001",
                "rule" => ""
            ],
            [
                "key" => "waktu_audit_iso_14001",
                "rule" => ""
            ],
            [
                "key" => "waktu_audit_iso_45001",
                "rule" => ""
            ],
            [
                "key" => "waktu_audit_iso_9001",
                "rule" => ""
            ],
            // -- justifikasi pengurangan
            ["key" => "kompleksitas_rendah_iso_9001", "rule" => ""],
            ["key" => "lokasi_kecil", "rule" => ""],
            ["key" => "kematangan_sistem_manajemen", "rule" => ""],
            ["key" => "pernah_diaudit_b4t", "rule" => ""],
            ["key" => "tersertifikasi_pihak_ketiga", "rule" => ""],
            ["key" => "proses_otomasi_tinggi", "rule" => ""],
            ["key" => "staff_luar_kantor", "rule" => ""],
            ["key" => "pengurangan_lainnya", "rule" => ""],
            ["key" => "persentasi_pengurangan", "rule" => ""],
            // -- justifikasi penambahan
            ["key" => "resiko_tinggi_iso_9001", "rule" => ""],
            ["key" => "kegiatan_sama_beda_lokasi", "rule" => ""],
            ["key" => "bahasa_staf_lebih_dari_satu", "rule" => ""],
            ["key" => "lokasi_luas", "rule" => ""],
            ["key" => "peraturan_ketat", "rule" => ""],
            ["key" => "proses_kompleks", "rule" => ""],
            ["key" => "lokasi_non_permanen", "rule" => ""],
            ["key" => "penambahan_lainnya", "rule" => ""],
            ["key" => "persentasi_penambahan", "rule" => ""],
            // -- summary
            ["key" => "total_penyesuaian", "rule" => ""],
            ["key" => "summary_waktu_audit_iso_9001", "rule" => ""],
            ["key" => "summary_waktu_audit_iso_14001", "rule" => ""],
            ["key" => "summary_waktu_audit_iso_45001", "rule" => ""],
            ["key" => "summary_total", "rule" => ""],
            ["key" => "resertifikasi_iso_9001", "rule" => ""],
            ["key" => "resertifikasi_iso_14001", "rule" => ""],
            ["key" => "resertifikasi_iso_45001", "rule" => ""],
            ["key" => "resertifikasi_total", "rule" => ""],
            // Justifikasi Pengurangan Waktu Audit Integrasi
            ["key" => "menerapkan_integrasi", "rule" => ""],
            ["key" => "sistem_diintegrasi", "rule" => ""],
            ["key" => "jumlah_sistem", "rule" => ""],
            // -- tingkat integrasi
            ["key" => "integrasi_dokumen", "rule" => ""],
            ["key" => "integrasi_tinjauan_manajemen", "rule" => ""],
            ["key" => "integrasi_internal_audit", "rule" => ""],
            ["key" => "integrasi_kebijakan", "rule" => ""],
            ["key" => "integrasi_sistem_proses", "rule" => ""],
            ["key" => "persentase_tingkat_integrasi", "rule" => ""],
            ["key" => "jumlah_auditor", "rule" => ""],
            ["key" => "auditor_ids", "rule" => ""],
            ["key" => "kemampuan_auditor", "rule" => ""],
            ["key" => "pengurangan_waktu_integrasi", "rule" => ""],
            // Waktu Audit Akhir
            ["key" => "final_total_waktu_audit", "rule" => ""],
            ["key" => "awal_tahap_1_perhitungan", "rule" => ""],
            ["key" => "awal_tahap_1_penyesuaian", "rule" => ""],
            ["key" => "awal_tahap_2_perhitungan", "rule" => ""],
            ["key" => "awal_tahap_2_penyesuaian", "rule" => ""],
            ["key" => "resertifikasi_tahap_1_perhitungan", "rule" => ""],
            ["key" => "resertifikasi_tahap_1_penyesuaian", "rule" => ""],
            ["key" => "resertifikasi_tahap_2_perhitungan", "rule" => ""],
            ["key" => "resertifikasi_tahap_2_penyesuaian", "rule" => ""],
            ["key" => "survailen_tahap_1_perhitungan", "rule" => ""],
            ["key" => "survailen_tahap_1_penyesuaian", "rule" => ""],
            ["key" => "survailen_tahap_2_perhitungan", "rule" => ""],
            ["key" => "survailen_tahap_2_penyesuaian", "rule" => ""],
            ["key" => "justifikasi_waktu_audit", "rule" => ""],
            // Kesimpulan Kajian Aplikasi
            ["key" => "aplikasi_sertifikasi", "rule" => ""],
            ["key" => "nomor_sertifikasi", "rule" => ""],
            // Validasi Kajian Aplikasi
            ["key" => "dikaji_oleh_1", "rule" => ""],
            ["key" => "dikaji_oleh_2", "rule" => ""],
            ["key" => "disetujui_oleh", "rule" => ""],
        ];

        foreach($forms as $item){
            DB::table($table)->insert([
                'section_id' => 3,
                'key' => $item["key"],
                'rule' => $item["rule"] ? $item["rule"] : null,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
