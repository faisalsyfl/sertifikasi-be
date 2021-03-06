<?php

namespace App\Api\V1\Controllers;

use App\Models\Auditor;
use App\Models\Competence;
use App\Models\SectionStatus;
use Illuminate\Http\Request;
use Validator, Config, DB;
use App\Http\Controllers\Controller;
use App\Traits\RestApi;
use App\Models\SectionForm;
use App\Models\SectionFormValue;

class Qsc3 extends Controller
{
    use RestApi;

    public function list($request, $id)
    {
        $section = 3;
        $section_status_id = SectionStatus::where('transaction_id', $id)->where('section_id', $section)->first();
        $existing = [];
        if ($section_status_id) {
            $existing = SectionFormValue::where('section_status_id', $section_status_id->id)->get();
            if (count($existing) > 0) {
                $existing = $existing->toArray();
            }
        } else {
            return ["status" => false, "data" => "Gagal Mendapatkan Detail Form Step 3"];
        }

        return ["status" => true, "data" => $existing];
    }

    public function store($request)
    {
        # Merge Rule Validation
        $field = SectionForm::where('section_id', $request['section'])->whereNotNull('rule')->get()->toArray();
        $arrayRule = [];
        foreach ($field as $v) {
            $arrayRule[$v['key']] = $v['rule'];
        }

        $validator = Validator::make($request->input(), $arrayRule);
        if ($validator->fails()) {
            return ["status" => false, "error" => $validator->errors()->toArray()];
        }

        if (is_array($request->all()) && (count($request->all()) > 0)) {
            $section_status_id = $request->input("section_status_id");
            $section_status = SectionStatus::find($section_status_id);

            try {
                DB::transaction(function () use ($request, $section_status) {
                    foreach ($request->all() as $key => $v) {
                        $idFormValue = SectionForm::where('section_id', $request['section'])->where('key', $key)->first("id");
                        if (isset($idFormValue->id) && $idFormValue->id) {
                            $existing = SectionFormValue::where('section_form_id', $idFormValue->id)->where('section_status_id', $request['section_status_id'])->first();
                            #combo save and edit
                            $formValue = (isset($existing->id) && $existing->id) ? $existing : new SectionFormValue();
                            $formValue->section_form_id = $idFormValue->id;
                            $formValue->section_status_id =  $request['section_status_id'];
                            $formValue->value =  is_array($v) ? json_encode($v) : $v;
                            $formValue->save();
                        }
                    }

                    if($section_status->status < 2){
                        $section_status->update([
                            "status" => 1
                        ]);
                    }

                    // Update QSC 4 and 6
                    $section_statuses = SectionStatus::where("transaction_id",$section_status->transaction_id)
                        ->whereIn("section_id",[4,6])->get();
                    foreach ($section_statuses as $section_status){
                        TransactionController::preDefineSectionFormValue($section_status->id, true);
                    }
                });
                return ["status" => true, "data" => "Berhasil Menyimpan Data"];
            } catch (\Throwable $th) {
                #save to LOG
            }
        }

        return ["status" => false, "error" => "No Data!"];
    }

    /**
     * @OA\Get(
     *  path="/api/v1/qsc3/lingkup-suggestion",
     *  summary="Get the list of lingkup suggestion",
     *  tags={"Form-other"},
     *  @OA\Parameter(
     *      name="keyword",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="limit",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer",
     *           default="10"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="page",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer",
     *           default="1"
     *      )
     *   ),
     *  @OA\Response(response=200,description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  @OA\Response(response=201,description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  @OA\Response(response=401,description="Unauthenticated"),
     *  @OA\Response(response=400,description="Bad Request"),
     *  @OA\Response(response=404,description="not found"),
     *  @OA\Response(response=403,description="Forbidden"),
     *  security={{ "apiAuth": {} }}
     * )
     */

    public function getLingkupSuggestion(Request $request){
        $limit  = $request->has('limit') ? $request->limit : 10;
        $page   = $request->has('page') ? $request->page : 1;
        $keyword = $request->has('keyword') ? $request->keyword : "";
        $suggestions = [];

        if($keyword){
            // Find from existing lingkup values
            $lingkup_forms = SectionForm::where("key","ruang_lingkup")->select("id")->get();
            $lingkup_form_ids = [];
            foreach ($lingkup_forms as $row){
                array_push($lingkup_form_ids,$row->id);
            }

            $lingkup_values = SectionFormValue::whereIn("section_form_id",$lingkup_form_ids)
                ->where('value', 'LIKE', "%".$keyword."%")
                ->get();
            foreach ($lingkup_values as $item){
                array_push($suggestions, $item->value);
            }

            // Find from EA/NACE
            $sectors = Competence::whereIn("type",["NACE","Sektor"])
                ->where(function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%".$keyword."%")
                        ->orWhere('name_alt', 'LIKE', "%".$keyword."%");
                })
                ->get();
            foreach ($sectors as $sector){
                if(stripos($sector->name,$keyword) !== false){
                    array_push($suggestions, $sector->name);
                }elseif (stripos($sector->name_alt,$keyword) !== false){
                    array_push($suggestions, $sector->name_alt);
                }
            }

            sort($suggestions);
            return $this->output(array_slice($suggestions,($page - 1) * $limit),$limit);
        }else{
            return $this->output('Keyword Kosong');
        }
    }

    static function get_auditor_objects($ids = [])
    {
        $auditor_objects = [];

        if(count($ids) > 0 and $ids[0]){
            $auditors = Auditor::whereIn("id", $ids)->get();
            foreach ($auditors as $auditor) {
                array_push($auditor_objects, $auditor->toArray());
            }
        }

        return $auditor_objects;
    }

    static function get_location_object($id)
    {
        $location = \App\Models\FormLocation::with(['country', 'state', 'city'])->where('id', $id)->first();
        if ($location) {
            return $location->toArray();
        } else {
            return (object) [];
        }
    }

    static function get_competence_object($ids = [])
    {
        $competence_objects = [];
        if(count($ids) > 0 and $ids[0]){
            $competences = Competence::whereIn("id", $ids)->get();
            foreach ($competences as $competence) {
                array_push($competence_objects, $competence->toArray());
            }
        }

        return $competence_objects;
    }

    static function getKeyValueQSC3()
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
            "scope" => "-",
            "sektor_ea" => null,
            "sektor_nace" => null,
            "akreditasi_lingkup_kan" => false,
            "personil_kompeten" => false,
            "konflik_kepentingan" => false,
            "penjelasan_solusi" => "",
            "lokasi_id" => "-",
            "multi_site" => false,
            "audit_shift_1" => false,
            "audit_shift_2" => false,
            "audit_shift_3" => false,
            "risiko_iso_9001" => "",
            "kompleksitas_iso_14001" => 0,
            "kompleksitas_iso_45001" => 0,
            "jumlah_personil_efektif" => 0,
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
            "pengurangan_lainnya" => null,
            "persentasi_pengurangan" => null,
            "resiko_tinggi_iso_9001" => null,
            "kegiatan_sama_beda_lokasi" => null,
            "bahasa_staf_lebih_dari_satu" => null,
            "lokasi_luas" => null,
            "peraturan_ketat" => null,
            "proses_kompleks" => null,
            "lokasi_non_permanen" => null,
            "penambahan_lainnya" => null,
            "persentasi_penambahan" => null,
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
            "auditor_ids" => null,
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
            "justifikasi_waktu_audit" => null,
            "aplikasi_sertifikasi" => false,
            "nomor_sertifikasi" => "-",
            "dikaji_oleh_1" => "-",
            "dikaji_oleh_2" => "-",
            "disetujui_oleh" => "-",
            "audit_tahap_1" => true
        ];
    }
}
