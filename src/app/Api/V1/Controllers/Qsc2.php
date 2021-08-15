<?php

namespace App\Api\V1\Controllers;

use Validator, Config, DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\RestApi;
use App\Models\Contact;
use App\Models\SectionForm;
use App\Models\SectionFormValue;
use App\Models\SectionStatus;
use App\Models\FormDocument;
use App\Models\FormDocumentStatus;
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

          if ($section_status->status < 2) {
            $section_status->update([
              "status" => 1
            ]);
          }
        });

        // Update QSC3
        $section_statuses = SectionStatus::where("transaction_id",$section_status->transaction_id)
          ->whereIn("section_id",[3,6])->first();
        foreach ($section_statuses as $section_status){
          TransactionController::preDefineSectionFormValue($section_status->id, true);
        }

        return ["status" => true, "data" => "Berhasil Menyimpan Data"];
      } catch (\Throwable $th) {
        #save to LOG
      }
    }

    return ["status" => false, "error" => "No Data!"];
  }

  /**
   * @OA\Get(
   *  path="/api/v1/qsc2/documents",
   *  summary="List document",
   *  tags={"Form-other"},
   *  @OA\Parameter(
   *      name="transaction_id",
   *      in="query",
   *      required=true,
   *      @OA\Schema(
   *           type="integer"
   *      )
   *   ),
   *  @OA\Parameter(
   *      name="section_id",
   *      in="query",
   *      required=true,
   *      @OA\Schema(
   *           type="integer"
   *      )
   *   ),
   *  @OA\Response(response=401,description="Unauthenticated"),
   *  @OA\Response(response=400,description="Bad Request"),
   *  @OA\Response(response=404,description="not found"),
   *  @OA\Response(response=403,description="Forbidden"),
   *  security={{ "apiAuth": {} }}
   * )
   */
  public function documentsList(Request $request)
  {
    $validator = Validator::make($request->input(), ['transaction_id' => 'required', 'section_id' => 'required']);
    if ($validator->fails()) {
      return $this->errorRequest(422, 'Validation Error', $validator->errors()->toArray());
    }

    $res = FormDocument::where('transaction_id', $request->transaction_id)->where('section_id', $request->section_id)->get();
    $res = $this->transformDoc($res->toArray(), $request->transaction_id, $request->section_id);
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

  /**
   * @OA\Delete(
   *  path="/api/v1/qsc2/documents",
   *  summary="Delete document",
   *  tags={"Form-other"},
   *  @OA\Parameter(
   *      name="id",
   *      in="query",
   *      required=true,
   *      description="",
   *      @OA\Schema(
   *           type="integer",
   *           format="int64"
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
  public function documentsDelete(Request $request)
  {
    try {
      if (isset($request->id) && $request->id) {
        $res = FormDocument::find($request->id);
        if ($res) {
          $res->delete();
        } else {
          return $this->errorRequest(422, 'Gagal Menghapus Data, Id tidak tersedia');
        }
        return $this->output('Berhasil menghapus data');
      }

      return $this->output('ID Kosong');
    } catch (\Throwable $th) {
      return $this->errorRequest(500, 'Unexpected error');
    }
  }

  /**
   * @OA\Post(
   *  path="/api/v1/qsc2/documents/edit",
   *  summary="Store Data document",
   *  tags={"Form-other"},
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\MediaType(
   *             mediaType="multipart/form-data",
   *             @OA\Schema(
   *                 @OA\Property(
   *                     description="file to upload",
   *                     property="file",
   *                     type="file",
   *                     format="file",
   *                 )
   *             )
   *         )
   *     ),
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
  public function documentUpdate(Request $request)
  {
    try {
      if (isset($request->id) && $request->id) {
        $doc = FormDocument::where('id', $request->id)->first();
        $insert = $request->all();
        if ($request->file('file')) {
          $file = $request->file('file');
          $file_hash                  = 'form_document_' . $this->hash_filename();
          $file_info['file_hash']     = str_replace(' ', '', trim($file_hash . "." . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION)));
          $save = Storage::disk('local')->put('public/form_document/' . $file_info['file_hash'], file_get_contents($file));
          if ($save) {
            $insert['name'] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $insert['file_hash'] = $file_info['file_hash'];
            $insert['file_type'] = $file->getClientOriginalExtension();
            $insert['file_size'] = $file->getSize();
            $insert['created_by'] = Auth::user()->id;
            $insert['transaction_id'] = $doc->transaction_id;
            $insert['section_id'] = $doc->section_id;
            unset($insert['file']);
          }
        }
        if ($doc) {
          $doc->update($insert);
        } else {
          return $this->errorRequest(422, 'Gagal Menghapus Data, Id tidak tersedia');
        }
        return $this->output('Berhasil Merubah data');
      }

      return $this->output('Id Kosong');
    } catch (\Throwable $th) {
      return $this->errorRequest(500, 'Unexpected error');
    }
  }

  public function documentUpdateStatus(Request $request)
  {
    // try {
    $validator = Validator::make($request->input(), ['transaction_id' => 'required', 'section_id' => 'required', 'type' => 'required', 'status' => 'required']);
    if ($validator->fails()) {
      return $this->errorRequest(422, 'Validation Error', $validator->errors()->toArray());
    }

    $doc = $this->getStatusDocument($request->transaction_id, $request->section_id, $request->type);

    if ($doc) {
      $insert['transaction_id'] = $request->transaction_id;
      $insert['section_id'] = $request->section_id;
      $insert['type'] = $this->docType($request->type);
      $insert['status'] = $request->status;
      $insert['created_by'] = Auth::user()->id;
      $doc->update($insert);
      return $this->output('Berhasil Merubah data');
    } else {
      $doc = new FormDocumentStatus();
      $doc->transaction_id = $request->transaction_id;
      $doc->section_id = $request->section_id;
      $doc->type =  $this->docType($request->type);
      $doc->status = $request->status;
      $doc->created_by = Auth::user()->id;
      $doc->save();
      return $this->output('Berhasil Menambah data');
    }
    // } catch (\Throwable $th) {
    //   return $this->errorRequest(500, 'Unexpected error');
    // }
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

  static function getContact($ids)
  {
    $Contact = [];
    if ($ids)
      $Contact = Contact::where("id", $ids)->first();
    return $Contact;
  }

  private function transformDoc($file, $transaction_id, $section_id)
  {
    $init = array('status' => 0, 'files' => []);
    $doc_type = array();
    // $doc_type['AKTE_NOTARIS'] = $init;
    // $doc_type['STRUKTUR_ORGANISASI'] = $init;
    // $doc_type['INTERAKSI_PROSES'] = $init;
    // $doc_type['DIGARAM_ALIR'] = $init;
    // $doc_type['LAYOUT_AREA'] = $init;
    // $doc_type['REKAMAN_INTERNAL'] = $init;
    // $doc_type['TINJAUAN_MANAGEMEN'] = $init;
    $doc_type['dokumen_1'] = $init;
    $doc_type['dokumen_2'] = $init;
    $doc_type['dokumen_3'] = $init;
    $doc_type['dokumen_4'] = $init;
    $doc_type['dokumen_5'] = $init;
    $doc_type['dokumen_6'] = $init;
    $doc_type['dokumen_7'] = $init;

    foreach ($file as $value) {
      switch ($value['type']) {
        case 'QSC_2_DOC_AKTE_NOTARIS';
          $value['file_url'] = $this->assignUrl($value['file_hash']);
          $res = $this->getStatusDocument($value['transaction_id'], $value['section_id'], $value['type']);
          $status = isset($res->status) && $res->status ? $res->status : 0;
          $doc_type['dokumen_1']['status'] = $status;
          $doc_type['dokumen_1']['files'][] = $value;
          break;
        case 'QSC_2_DOC_STRUKTUR_ORGANISASI';
          $value['file_url'] = $this->assignUrl($value['file_hash']);
          $res = $this->getStatusDocument($value['transaction_id'], $value['section_id'], $value['type']);
          $status = isset($res->status) && $res->status ? $res->status : 0;
          $doc_type['dokumen_2']['status'] = $status;
          $doc_type['dokumen_2']['files'][] = $value;
          break;
        case 'QSC_2_DOC_INTERAKSI_PROSES';
          $value['file_url'] = $this->assignUrl($value['file_hash']);
          $res = $this->getStatusDocument($value['transaction_id'], $value['section_id'], $value['type']);
          $status = isset($res->status) && $res->status ? $res->status : 0;
          $doc_type['dokumen_3']['status'] = $status;
          $doc_type['dokumen_3']['files'][] = $value;
          break;
        case 'QSC_2_DOC_DIGARAM_ALIR';
          $value['file_url'] = $this->assignUrl($value['file_hash']);
          $res = $this->getStatusDocument($value['transaction_id'], $value['section_id'], $value['type']);
          $status = isset($res->status) && $res->status ? $res->status : 0;
          $doc_type['dokumen_4']['status'] = $status;
          $doc_type['dokumen_4']['files'][] = $value;
          break;
        case 'QSC_2_DOC_LAYOUT_AREA';
          $value['file_url'] = $this->assignUrl($value['file_hash']);
          $res = $this->getStatusDocument($value['transaction_id'], $value['section_id'], $value['type']);
          $status = isset($res->status) && $res->status ? $res->status : 0;
          $doc_type['dokumen_5']['status'] = $status;
          $doc_type['dokumen_5']['files'][] = $value;
          break;
        case 'QSC_2_DOC_REKAMAN_INTERNAL';
          $value['file_url'] = $this->assignUrl($value['file_hash']);
          $res = $this->getStatusDocument($value['transaction_id'], $value['section_id'], $value['type']);
          $status = isset($res->status) && $res->status ? $res->status : 0;
          $doc_type['dokumen_6']['status'] = $status;
          $doc_type['dokumen_6']['files'][] = $value;
          break;
        case 'QSC_2_DOC_TINJAUAN_MANAGEMEN';
          $value['file_url'] = $this->assignUrl($value['file_hash']);
          $res = $this->getStatusDocument($value['transaction_id'], $value['section_id'], $value['type']);
          $status = isset($res->status) && $res->status ? $res->status : 0;
          $doc_type['dokumen_7']['status'] = $status;
          $doc_type['dokumen_7']['files'][] = $value;
          break;
      }
    }
    return $doc_type;
  }

  private function assignUrl($file_hash)
  {
    if ($file_hash)
      return asset('storage/form_document/' .  $file_hash);
  }

  private function getStatusDocument($transaction_id, $section_id, $type)
  {
    return FormDocumentStatus::where('transaction_id', $transaction_id)
      ->where('section_id', $section_id)
      ->where('type', $type)->first();
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
