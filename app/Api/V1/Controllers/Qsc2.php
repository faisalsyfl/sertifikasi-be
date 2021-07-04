<?php

namespace App\Api\V1\Controllers;

use Validator, Config, DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\RestApi;
use App\Models\SectionForm;
use App\Models\SectionFormValue;
use App\Models\SectionStatus;
use App\Models\FormDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Qsc2 extends Controller
{
  use RestApi;

  public function list($request, $id)
  {
    $section = 2;
    $section_status_id = SectionStatus::where('transaction_id', $id)->where('section_id', $section)->first();
    $existing = [];
    if ($section_status_id) {
      $existing = SectionFormValue::where('section_status_id', $section_status_id->id)->get();
      if (count($existing) > 0) {
        $existing = $existing->toArray();
      }
    } else {
      return ["status" => false, "data" => "Gagal Mendapatkan Detail Form Step 2"];
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

    $arrayRule = array_merge($arrayRule, Config::get('validation_rules.form_qsc_2.validation_rules'));

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

  public function documentsList(Request $request)
  {
    $validator = Validator::make($request->input(), ['transaction_id' => 'required', 'section_id' => 'required']);
    if ($validator->fails()) {
      return $this->errorRequest(422, 'Validation Error', $validator->errors()->toArray());
    }

    $res = FormDocument::where('transaction_id', $request->transaction_id)->where('section_id', $request->section_id)->get();
    $res = $this->transformDoc($res->toArray());
    return $this->output($res);
  }

  public function documentsUpload(Request $request)
  {
    for ($i = 1; $i <= 7; $i++) {
      $name = 'dokumen_' . $i;
      if ($request->$name && is_array($request->$name)) {
        $validator = Validator::make($request->input(), ['transaction_id' => 'required', 'section_id' => 'required', $name => 'image']);
        if ($validator->fails()) {
          return $this->errorRequest(422, 'Validation Error', $validator->errors()->toArray());
        }
      }
    }

    for ($i = 1; $i <= 7; $i++) {
      $name = 'dokumen_' . $i;
      if ($request->$name && is_array($request->$name)) {
        foreach ($request->$name as $value) {
          $status = $this->saveDoc($value, $name, $request);
        }
      }
    }

    $res = FormDocument::where('transaction_id', $request->transaction_id)->where('section_id', $request->section_id)->get();
    return $this->output($res);
  }

  private function saveDoc($file, $name, $request)
  {
    $file_hash                  = 'form_document_' . $this->hash_filename();
    $file_info['file_hash']     = str_replace(' ', '', trim($file_hash . "." . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION)));
    $save = Storage::disk('local')->put('public/form_document/' . $file_info['file_hash'], file_get_contents($file));
    if ($save) {
      $insert['name'] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
      $insert['type'] = $this->docType($name);
      $insert['file_hash'] = $file_info['file_hash'];
      $insert['file_type'] = $file->getClientOriginalExtension();
      $insert['file_size'] = $file->getSize();
      $insert['created_by'] = Auth::user()->id;
      $insert['transaction_id'] = $request->transaction_id;
      $insert['section_id'] = $request->section_id;
      $doc = new FormDocument($insert);
      if ($doc->save())
        return true;
    }
    return false;
  }

  private function transformDoc($file)
  {
    $data = [];
    foreach ($file as $value) {
      switch ($value['type']) {
        case 'QSC_2_DOC_AKTE_NOTARIS';
          $data['dokumen_1'][] = $value;
          break;
        case 'QSC_2_DOC_STRUKTUR_ORGANISASI';
          $data['dokumen_2'][] = $value;
          break;
        case 'QSC_2_DOC_INTERAKSI_PROSES';
          $data['dokumen_3'][] = $value;
          break;
        case 'QSC_2_DOC_DIGARAM_ALIR';
          $data['dokumen_4'][] = $value;
          break;
        case 'QSC_2_DOC_LAYOUT_AREA';
          $data['dokumen_6'][] = $value;
          break;
        case 'QSC_2_DOC_REKAMAN_INTERNAL';
          $data['dokumen_6'][] = $value;
          break;
        case 'QSC_2_DOC_TINJAUAN_MANAGEMEN';
          $data['dokumen_7'][] = $value;
          break;
      }
    }

    return $data;
  }

  private function docTypeReverse($file)
  {
    switch ($file['type']) {
      case 'QSC_2_DOC_AKTE_NOTARIS';
        return 'dokumen_1';
        break;
      case 'QSC_2_DOC_STRUKTUR_ORGANISASI';
        return 'dokumen_2';
        break;
      case 'QSC_2_DOC_INTERAKSI_PROSES';
        return 'dokumen_3';
        break;
      case 'QSC_2_DOC_DIGARAM_ALIR';
        return 'dokumen_4';
        break;
      case 'QSC_2_DOC_LAYOUT_AREA';
        return 'dokumen_5';
        break;
      case 'QSC_2_DOC_REKAMAN_INTERNAL';
        return 'dokumen_6';
        break;
      case 'QSC_2_DOC_TINJAUAN_MANAGEMEN';
        return 'dokumen_7';
        break;
    }
  }

  private function docType($name)
  {
    switch ($name) {
      case 'dokumen_1':
        return 'QSC_2_DOC_AKTE_NOTARIS';
        break;
      case 'dokumen_2':
        return 'QSC_2_DOC_STRUKTUR_ORGANISASI';
        break;
      case 'dokumen_3':
        return 'QSC_2_DOC_INTERAKSI_PROSES';
        break;
      case 'dokumen_4':
        return 'QSC_2_DOC_DIGARAM_ALIR';
        break;
      case 'dokumen_5':
        return 'QSC_2_DOC_LAYOUT_AREA';
        break;
      case 'dokumen_6':
        return 'QSC_2_DOC_REKAMAN_INTERNAL';
        break;
      case 'dokumen_7':
        return 'QSC_2_DOC_TINJAUAN_MANAGEMEN';
        break;
    }
  }
}
