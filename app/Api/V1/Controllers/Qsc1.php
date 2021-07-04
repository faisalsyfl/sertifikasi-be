<?php

namespace App\Api\V1\Controllers;

use Validator, Config, DB;
use App\Http\Controllers\Controller;
use App\Traits\RestApi;
use App\Models\SectionForm;
use App\Models\SectionFormValue;
use App\Models\SectionStatus;
use App\Models\Organization;
use App\Models\Auditi;
use App\Models\Transaction;
use App\Models\Section;

class Qsc1 extends Controller
{
    use RestApi;

    public function list($request,$id)
    {
        //Static Section = 1
        $section = 1;
        $section_status_id = SectionStatus::where('transaction_id',$id)->where('section_id',$section)->first();
        if($section_status_id){
            $existing = SectionFormValue::where('section_status_id', $section_status_id->id)->get();
        }else{
            return ["status" => false, "data" => "Gagal Mendapatkan Detail Form Step 1"];
        }
        return ["status" => true, "data" => $existing->toArray()];
    }

    public function store($request)
    {
        # Merge Rule Validation
        $field = SectionForm::where('section_id', $request['section'])->whereNotNull('rule')->where("rule","!=","")->get()->toArray();
        $arrayRule = [];
        $map = [];
        foreach ($field as $v) {
            $arrayRule[$v['key']] = $v['rule'];
        }

        $insert = $request->all();
        if($request->has('organization_id')){
            $org = Organization::with('city','state','country')->where('id',$request->organization_id)->first();
            $map  = $this->mapOrg($org->toArray());
        }
        if($request->has('auditi_id')){
            // dd($request->auditi_id);
            $Auditi = Auditi::with('city','state','country')->where('id',$request->auditi_id)->first();
            $map2  = $this->mapAuditi($Auditi->toArray());
            $map = array_merge($map,$map2);
        }
        $validator = Validator::make($map, $arrayRule);
        if ($validator->fails()) {
            return $this->errorRequest(422,"Validation Error",$validator);
        }
        if(!$request->has('transaction_id')){
            $transaction_id = $this->generateTransaction($request);
            $this->generateSectionStatus($transaction_id);
        }else{
            $transaction_id = $request->transaction_id;
            $trans = Transaction::find($transaction_id);
            $trans->update([
                'auditi_id' => $request->auditi_id,
                'organization_id' => $request->organization_id
            ]);
        }
        if (is_array($map) && (count($map) > 0)) {
            $section_status_id = SectionStatus::where('transaction_id',$transaction_id)->where('section_id',$insert['section'])->first()->id;
            try {
                DB::transaction(function () use ($map,$insert,$section_status_id) {
                    foreach ($map as $key => $v) {
                        $idFormValue = SectionForm::where('section_id', $insert['section'])->where('key', $key)->first("id");
                        if (isset($idFormValue->id) && $idFormValue->id) {
                            $existing = SectionFormValue::where('section_form_id', $idFormValue->id)->where('section_status_id', $section_status_id)->first();
                            #combo save and edit
                            $formValue = (isset($existing->id) && $existing->id) ? $existing : new SectionFormValue();
                            $formValue->section_form_id = $idFormValue->id;
                            $formValue->section_status_id =  $section_status_id;
                            $formValue->value =  is_array($v) ? json_encode($v) : $v;
                            $formValue->save();
                        }
                    }
                });
                return ["status" => true, "data" => ['transaction_id' => $transaction_id]];
            } catch (\Throwable $th) {
                #save to LOG
            }
        }

        return ["status" => false, "error" => "No Data!"];
    }

    private function mapOrg($arr){
        $map['nama'] = $arr['name'];
        $map['npwp'] = $arr['npwp'];
        $map['tipe'] = $arr['type'];
        $map['website'] = $arr['website'];
        $map['email'] = $arr['email'];
        $map['alamat'] = $arr['address'];
        $map['telp'] = $arr['telp'];
        $map['kode_pos'] = $arr['postcode'];
        $map['kota'] = $arr['city']['name'];
        $map['provinsi'] = $arr['state']['name'];
        $map['negara'] = $arr['country']['name'];

        return $map;
    }

    private function mapAuditi($arr){
        $map['nama_klien'] = $arr['name'];
        $map['tipe_klien'] = $arr['type'];
        $map['website_klien'] = $arr['website'];
        $map['email_klien'] = $arr['email'];
        $map['alamat_klien'] = $arr['address'];
        $map['telp_klien'] = $arr['telp'];
        $map['kode_pos_klien'] = $arr['postcode'];
        $map['kota_klien'] = $arr['city']['name'];
        $map['provinsi_klien'] = $arr['state']['name'];
        $map['negara_klien'] = $arr['country']['name'];

        return $map;
    }

    private function generateTransaction($request){

        $transaction = new Transaction(['organization_id' => $request->organization_id, 'auditi_id' => $request->auditi_id]);
        $transaction->code   = 'SC';
        $transaction->save();

        return $transaction->id;
    }
    private function generateSectionStatus($transaction_id){
        $section = Section::all();
        for($i = 0 ; $i<count($section); $i ++){
            $sectionStatus =  new SectionStatus();
            $sectionStatus->transaction_id = $transaction_id;
            $sectionStatus->section_id = $section[$i]->id;
            if($section[$i]->name == 'Pendaftaran'){
                $sectionStatus->status = 3;
            }else{
                $sectionStatus->status = 0;
            }
            $sectionStatus->save();

            // Pre-define section form value
            $this->preDefineSectionFormValue($sectionStatus->id, $section[$i]->id);
        }
    }

    private function preDefineSectionFormValue($sectionStatusId, $sectionId){
        if($sectionStatusId and $sectionId){
            switch ($sectionId){
                case 3:
                    $keyValues = $this->getKeyValueQSC3();
                    break;
                default:
                    $keyValues = [];
                    break;
            }

            foreach ($keyValues as $key => $value){
                if($key == "nama_klien" and $sectionId != 1){
                    $namaKlien = SectionFormValue::join("section_form", "section_form.id", "=", "section_form_value.section_form_id")
                        ->where("section_form.section_id",1)
                        ->where("section_form.key","name_klien")
                        ->select("section_form_value.*")
                        ->first();
                    if($namaKlien){
                        $value = $namaKlien->value;
                    }
                }

                $sectionForm = SectionForm::where("section_id",$sectionId)
                    ->where("key", $key)->first();
                if($sectionForm){
                    $sectionValue = new SectionFormValue();
                    $sectionValue->section_status_id = $sectionStatusId;
                    $sectionValue->section_form_id = $sectionForm->id;
                    $sectionValue->value = $value;
                    $sectionValue->save();
                }
            }
        }
    }

    private function getKeyValueQSC3()
    {
        return [
            "nama_klien" => "-",
            "status_aplikasi_sertifikasi" => "SERTIFIKASI_AWAL",
            "manajemen_mutu" => false,
            "manajemen_lingkungan" => false,
            "manajemen_keselamatan" => false,
            "industri_hijau" => false,
            "audit_single" => false,
            "audit_joint" => false,
            "audit_combination" => false,
            "audit_integration" => false,
            "lingkup" => "-",
            "sektor_ea" => "-",
            "sektor_nace" => "-",
            "akreditasi_lingkup_kan" => false,
            "personil_kompeten" => false,
            "konflik_kepentingan" => false,
            "penjelasan_solusi" => "-",
            "lokasi_id" => "-",
            "multi_site" => false,
            "audit_shift_1" => false,
            "audit_shift_2" => false,
            "audit_shift_3" => false,
            "risiko_iso_9001" => "-",
            "kompleksitas_iso_14001" => "-",
            "kompleksitas_iso_45001" => "-",
            "jumlah_personil_management" => 0,
            "jumlah_personil_administrasi" => 0,
            "jumlah_personil_part_time" => 0,
            "jumlah_personil_non_permanen" => 0,
            "jumlah_personil_shift_1" => 0,
            "jumlah_personil_shift_2" => 0,
            "jumlah_personil_shift_3" => 0,
            "keterangan_shift" => "-",
            "waktu_audit_iso_9001" => 0,
            "waktu_audit_iso_14001" => 0,
            "waktu_audit_iso_45001" => 0,
            "kompleksitas_rendah_iso_9001" => null,
            "lokasi_kecil" => null,
            "kematangan_sistem_manajemen" => null,
            "pernah_diaudit_b4t" => null,
            "tersertifikasi_pihak_ketiga" => null,
            "proses_otomasi_tinggi" => null,
            "staff_luar_kantor" => null,
            "pengurangan_lainnya" => "-",
            "persentasi_pengurangan" => 0,
            "resiko_tinggi_iso_9001" => false,
            "kegiatan_sama_beda_lokasi" => false,
            "bahasa_staf_lebih_dari_satu" => false,
            "lokasi_luas" => false,
            "peraturan_ketat" => false,
            "proses_kompleks" => false,
            "lokasi_non_permanen" => false,
            "penambahan_lainnya" => "-",
            "persentasi_penambahan" => 0,
            "total_penyesuaian" => 0,
            "summary_waktu_audit_iso_9001" => 0,
            "summary_waktu_audit_iso_14001" => 0,
            "summary_waktu_audit_iso_45001" => 0,
            "summary_total" => 0,
            "resertifikasi_iso_9001" => 0,
            "resertifikasi_iso_14001" => 0,
            "resertifikasi_iso_45001" => 0,
            "resertifikasi_total" => 0,
            "menerapkan_integrasi" => false,
            "sistem_diintegrasi" => "-",
            "jumlah_sistem" => 0,
            "integrasi_dokumen" => false,
            "integrasi_tinjauan_manajemen" => false,
            "integrasi_internal_audit" => false,
            "integrasi_kebijakan" => false,
            "integrasi_sistem_proses" => false,
            "persentase_tingkat_integrasi" => 0,
            "jumlah_auditor" => 0,
            "auditor_ids" => "1,2,3",
            "kemampuan_auditor" => 0,
            "pengurangan_waktu_integrasi" => 0,
            "final_total_waktu_audit" => 0,
            "awal_tahap_1_perhitungan" => 0,
            "awal_tahap_1_penyesuaian" => 0,
            "awal_tahap_2_perhitungan" => 0,
            "awal_tahap_2_penyesuaian" => 0,
            "resertifikasi_tahap_1_perhitungan" => 0,
            "resertifikasi_tahap_1_penyesuaian" => 0,
            "resertifikasi_tahap_2_perhitungan" => 0,
            "resertifikasi_tahap_2_penyesuaian" => 0,
            "survailen_tahap_1_perhitungan" => 0,
            "survailen_tahap_1_penyesuaian" => 0,
            "survailen_tahap_2_perhitungan" => 0,
            "survailen_tahap_2_penyesuaian" => 0,
            "justifikasi_waktu_audit" => 0,
            "aplikasi_sertifikasi" => false,
            "nomor_sertifikasi" => "-",
            "dikaji_oleh_1" => "-",
            "dikaji_oleh_2" => "-",
            "disetujui_oleh" => "-"
        ];
    }
}
