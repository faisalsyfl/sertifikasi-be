<?php

namespace App\Api\V1\Controllers;

use App\Models\Auditi;
use App\Models\Auditor;
use App\Models\Competence;
use App\Models\Payment;
use App\Models\SectionStatus;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Validator, Config, DB;
use App\Http\Controllers\Controller;
use App\Traits\RestApi;
use App\Models\SectionForm;
use App\Models\SectionFormValue;
use Carbon\Carbon;

class Qsc7 extends Controller
{
    use RestApi;

    public function list($request, $id)
    {
        $section = 7;
        $section_status_id = SectionStatus::where('transaction_id', $id)->where('section_id', $section)->first();
        $existing = [];
        if ($section_status_id) {
            $existing = SectionFormValue::where('section_status_id', $section_status_id->id)->get();
            if (count($existing) > 0) {
                $existing = $existing->toArray();
            }
        } else {
            return ["status" => false, "data" => "Gagal Mendapatkan Detail Form Step 7"];
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
                });
                return ["status" => true, "data" => "Berhasil Menyimpan Data"];
            } catch (\Throwable $th) {
                #save to LOG
            }
        }

        return ["status" => false, "error" => "No Data!"];
    }

    static function getKeyValueQSC7()
    {
        return [
            "nomor_sertifikat" => "-",
            "start_sertifikat" => "-",
            "end_sertifikat" => "-",
            "draft_sertifikat" => "-",
            "published_sertifikat" => "-"
        ];
    }
}
