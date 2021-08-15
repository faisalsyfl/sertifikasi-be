<?php

namespace App\Api\V1\Controllers;

use App\Models\Auditi;
use App\Models\Auditor;
use App\Models\Competence;
use App\Models\FormDocument;
use App\Models\Payment;
use App\Models\SectionStatus;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Validator, Config, DB;
use App\Http\Controllers\Controller;
use App\Traits\RestApi;
use App\Models\SectionForm;
use App\Models\SectionFormValue;
use Carbon\Carbon;

class Qsc6 extends Controller
{
    use RestApi;

    public function list($request, $id)
    {
        $section = 6;
        $section_status_id = SectionStatus::where('transaction_id', $id)->where('section_id', $section)->first();
        $existing = [];
        if ($section_status_id) {
            $existing = SectionFormValue::where('section_status_id', $section_status_id->id)->get();
            if (count($existing) > 0) {
                $existing = $existing->toArray();
            }
        } else {
            return ["status" => false, "data" => "Gagal Mendapatkan Detail Form Step 6"];
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

    /**
     * @OA\Post(
     *  path="/api/v1/qsc6/upload",
     *  summary="Save and Upload File - Step 6",
     *  tags={"Form-other"},
     *  @OA\RequestBody(
     *     required=true,
   *         @OA\MediaType(
   *             mediaType="multipart/form-data",
   *             @OA\Schema(
   *                 @OA\Property(
   *                     description="file to upload",
   *                     property="evaluasi_file",
   *                     type="file",
   *                     format="file",
   *                 )
   *             )
   *         )
     *  ),
     *   @OA\Response(
     *     response=200,
     *     description="Success"
     *  ),
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

    /**
     * @OA\Post(
     *  path="/api/v1/qsc7/upload",
     *  summary="Save and Upload File - Step 7",
     *  tags={"Form-other"},
     * @OA\RequestBody(
     *     required=true,
   *         @OA\MediaType(
   *             mediaType="multipart/form-data",
   *             @OA\Schema(
   *                 @OA\Property(
   *                     description="file to upload",
   *                     property="evaluasi_file",
   *                     type="file",
   *                     format="file",
   *                 )
   *             )
   *         )
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="Success"
     *  ),
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

    public function filesUpload(Request $request)
    {
        $documents = [
            "evaluasi_file", "draft_sertifikat", "published_sertifikat"
        ];
        $file_urls = [];

        foreach ($documents as $document){
            if($request->$document){
                $file_urls[$document] = null;
                $file = $request->$document;
                $file_path = $this->saveFile($file, $document);
                if($file_path){
                    $file_urls[$document] = $file_path;
                }
            }
        }

        return $this->output($file_urls);
    }

    private function saveFile($file, $name)
    {
        $file_hash = 'document_'. $name . '_' . $this->hash_filename();
        $file_name = str_replace(' ', '', trim($file_hash . "." . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION)));
        $save = Storage::disk('local')->put('public/form_document/' . $file_name, file_get_contents($file));
        if ($save) {
            return asset("/storage/form_document/".$file_name);
        }
        return false;
    }

    private function docType($name)
    {
        switch ($name) {
            case 'evaluasi_file':
                return 'QSC_6_DOC_EVALUASI_FILE';
                break;
            case 'draft_sertifikat':
                return 'QSC_7_DOC_DRAFT_SERTIFIKAT';
                break;
            case 'published_sertifikat':
                return 'QSC_7_DOC_PUBLISHED_SERTIFIKAT';
                break;
            default:
                return 'default';
                break;
        }
    }

    static function getKeyValueQSC6()
    {
        return [
            "nama_klien" => "-",
            "alamat_klien" => "-",
            "status_aplikasi_sertifikasi" => "-",
            "lingkup" => "-",
            "scope" => "-",
            "sektor_ea" => "-",
            "sektor_nace" => "-",
            "nomor_sertifikasi" => "-",
            "evaluasi_file" => null,
        ];
    }
}
