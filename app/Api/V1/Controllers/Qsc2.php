<?php

namespace App\Api\V1\Controllers;

use Validator, Config, DB;
use App\Http\Controllers\Controller;
use App\Traits\RestApi;
use App\Models\SectionForm;
use App\Models\SectionFormValue;
use App\Models\SectionStatus;

class Qsc2 extends Controller
{
<<<<<<< develop
    use RestApi;

    public function list($request)
    {
        
    }

    public function store($request)
    {
        # Merge Rule Validation
        $field = SectionForm::where('section_id', $request['section'])->whereNotNull('rule')->get()->toArray();
        $arrayRule = [];
        foreach ($field as $v) {
            $arrayRule[$v['key']] = $v['rule'];
        }

        $arrayRule = array_merge($arrayRule, Config::get('validation_rules.form_qsc_2.validation_rules'));

        $validator = Validator::make($request->input(), $arrayRule);
        if ($validator->fails()) {
            return ["status" => false, "error" => $validator->errors()->toArray()];
        }

        if (is_array($request->all()) && (count($request->all()) > 0)) {
            $this->genereateSectionStatus();
            try {
                DB::transaction(function () use ($request) {
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
                });
                return ["status" => true, "data" => "Berhasil Menyimpan Data"];
            } catch (\Throwable $th) {
                #save to LOG
=======
  use RestApi;

  public function list($id)
  {
    $arra = '[
            {
              "section_id": "2",
              "key": "manajemen_mutu",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "manajemen_lingkungan",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "manajemen_keselamatan",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "industri_hijau",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "status_aplikasi_sertifikasi",
              "rule": "required"
            },
            {
              "section_id": "2",
              "key": "auditi_id",
              "rule": "required|exists:auditi,id"
            },
            {
              "section_id": "2",
              "key": "jumlah_personil_management",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "jumlah_personil_administrasi",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "jumlah_personil_part_time",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "jumlah_personil_non_permanen",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "jumlah_personil_shift_1",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "jumlah_personil_shift_2",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "jumlah_personil_shift_3",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "jumlah_personil_efektif",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "alamat_id",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "ruang_lingkup",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "informasi_tambahan_1",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "informasi_tambahan_2",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "informasi_tambahan_3",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "informasi_tambahan_4",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "informasi_tambahan_5",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "informasi_tambahan_6",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "informasi_tambahan_7",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "informasi_tambahan_8",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "informasi_tambahan_9",
              "rule": ""
            },
            {
              "section_id": "2",
              "key": "tanda_tangan_formulir",
              "rule": ""
>>>>>>> merge
            }
          ]';

    // dd(json_decode($arra, TRUE));
    $arr = json_decode($arra, TRUE);
    // dd($arr);
    foreach ($arr as $v) {
      dd([
        'section_id' => 2,
        'key' => $v["key"],
        'rule' => $v["rule"],
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
      ]);
    }
    $section = 2;
    $section_status_id = SectionStatus::where('transaction_id', $id)->where('section_id', $section)->first();
    if ($section_status_id) {
      $existing = SectionFormValue::where('section_status_id', $section_status_id->id)->get();
    } else {
      return ["status" => false, "data" => "Gagal Mendapatkan Detail Form Step 2"];
    }
    return ["status" => true, "data" => $existing->toArray()];
  }

  public function store($request)
  {
    # Merge Rule Validation
    $field = SectionForm::where('section_id', $request['section'])->whereNotNull('rule')->get()->toArray();
    $arrayRule = [];
    foreach ($field as $v) {
      $arrayRule[$v['key']] = $v['rule'];
    }

    $arrayRule = array_merge($arrayRule, Config::get('validation_rules.form_qsc_2.validation_rules'));

    $validator = Validator::make($request->input(), $arrayRule);
    if ($validator->fails()) {
      return ["status" => false, "error" => $validator->errors()->toArray()];
    }

    if (is_array($request->all()) && (count($request->all()) > 0)) {
      try {
        DB::transaction(function () use ($request) {
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
        });
        return ["status" => true, "data" => "Berhasil Menyimpan Data"];
      } catch (\Throwable $th) {
        #save to LOG
      }
    }

    return ["status" => false, "error" => "No Data!"];
  }
}
