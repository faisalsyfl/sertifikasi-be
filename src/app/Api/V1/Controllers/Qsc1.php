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
use Illuminate\Http\Request;
use Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Qsc1 extends Controller
{
    use RestApi;

    public function list($request, $id)
    {
        //Static Section = 1
        $section = 1;
        $section_status_id = SectionStatus::where('transaction_id', $id)->where('section_id', $section)->first();
        if ($section_status_id) {
            $existing = SectionFormValue::where('section_status_id', $section_status_id->id)->get();
        } else {
            return ["status" => false, "data" => "Gagal Mendapatkan Detail Form Step 1"];
        }
        return ["status" => true, "data" => $existing->toArray()];
    }
    /**
     * @OA\Post(
     *  path="/api/v1/qsc/store",
     *  summary="Save and edit - Step 1",
     *  tags={"Form"},
     *  @OA\Parameter(
     *      name="mode",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           default="QSC1"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="organization_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="auditi_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="auditi_status",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           default="seluruh/cabang"
     *      )
     *   ),
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

    public function store($request)
    {
        # Merge Rule Validation
        $field = SectionForm::where('section_id', $request['section'])->whereNotNull('rule')->where("rule", "!=", "")->get()->toArray();
        $arrayRule = [];
        $map = [];
        foreach ($field as $v) {
            $arrayRule[$v['key']] = $v['rule'];
        }

        $insert = $request->all();
        if ($request->has('organization_id')) {
            $org = Organization::with('city', 'state', 'country')->where('id', $request->organization_id)->first();
            $map  = $this->mapOrg($org->toArray());
        }
        if ($request->has('auditi_id')) {
            // dd($request->auditi_id);
            $Auditi = Auditi::with('city', 'state', 'country')->where('id', $request->auditi_id)->first();
            $map2  = $this->mapAuditi($Auditi->toArray());
            $map = array_merge($map, $map2);
        }
        $validator = Validator::make($map, $arrayRule);
        if ($validator->fails()) {
            return $this->errorRequest(422, "Validation Error", $validator);
        }
        $section_status_ids = [];
        $transaction_code = "-";
        if (!$request->has('transaction_id')) {
            $transaction = $this->generateTransaction($request);
            $transaction_id = $transaction->id;
            $transaction_code = $transaction->code;
            $section_status_ids = $this->generateSectionStatus($transaction_id);
        } else {
            $transaction_id = $request->transaction_id;
            $trans = Transaction::find($transaction_id);
            $transaction_code = $trans->code;
            $trans->update([
                'auditi_id' => $request->auditi_id,
                'organization_id' => $request->organization_id
            ]);
        }

        if (isset($insert["auditi_status"]) and $insert["auditi_status"]) {
            $map["auditi_status"] = $insert["auditi_status"];
        }
        if (isset($insert["send_to_email"]) and $insert["send_to_email"]) {
            $map["send_to_email"] = $insert["send_to_email"];
        }

        if (is_array($map) && (count($map) > 0)) {
            $section_status_id = SectionStatus::where('transaction_id', $transaction_id)->where('section_id', $insert['section'])->first()->id;
            try {
                DB::transaction(function () use ($map, $insert, $section_status_id) {
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

                // Pre-define section form value
                foreach ($section_status_ids as $section_status_id) {
                    TransactionController::preDefineSectionFormValue($section_status_id, false, [
                        '4' => [
                            'nomor_sertifikasi' => $transaction_code
                        ]
                    ]);
                }
                return ["status" => true, "data" => ['transaction_id' => $transaction_id, 'public_code' => strtoupper($transaction->public_code)]];
            } catch (\Throwable $th) {
                #save to LOG
            }
        }

        return ["status" => false, "error" => "No Data!"];
    }

    private function mapOrg($arr)
    {
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

    private function mapAuditi($arr)
    {
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

    private function generateTransaction($request)
    {

        $transaction = new Transaction(['organization_id' => $request->organization_id, 'auditi_id' => $request->auditi_id]);
        $transaction->code = 'SC';
        $transaction->public_code = self::generatePublicCode();
        $transaction->save();

        return $transaction;
    }
    private function generateSectionStatus($transaction_id)
    {
        $section_status_ids = [];
        $section = Section::all();
        for ($i = 0; $i < count($section); $i++) {
            $sectionStatus =  new SectionStatus();
            $sectionStatus->transaction_id = $transaction_id;
            $sectionStatus->section_id = $section[$i]->id;
            if ($section[$i]->name == 'Pendaftaran') {
                $sectionStatus->status = 3;
            } else {
                $sectionStatus->status = 0;
            }
            $sectionStatus->save();

            array_push($section_status_ids, $sectionStatus->id);
        }

        return $section_status_ids;
    }

    static function generatePublicCode()
    {
        $code = null;
        $unique = true;
        while ($unique) {
            $code = strtolower(substr(bin2hex(random_bytes(20)), 0, 5));
            $unique = Transaction::where("public_code", $code)->first();
        }

        return $code;
    }

    /**
     * @OA\Post(
     *  path="/api/v1/qsc/sendmail",
     *  summary="Send Email Pendaftaran",
     *  tags={"Form"},
     *  @OA\Parameter(
     *      name="transaction_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="booking_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="auditi_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *      )
     *   ),
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
    public function sendMail(Request $request)
    {
        $validate = $this->validateRequest(
            $request->all(),
            [
                'transaction_id' => 'required',
                'booking_code' => 'required',
                'auditi_id' => 'required|numeric',
            ]
        );
        if ($validate)
            return $this->errorRequest(422, 'Validation Error', $validate);

        $auditi = Auditi::find($request->auditi_id);
        if (!$auditi)
            return $this->errorRequest(422, 'Auditi Not Found', $validate);

        $data["name"] = $auditi->name;
        $data["alamat"] = $auditi->address;
        $data["email"] = $auditi->email;
        $data["kode_booking"] = $request->booking_code;

        $html = view('pendaftaran', $data)->render();
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions

        // dd(env('MAIL_HOST'));
        try {
            // Pengaturan Server
            // $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host =  env('MAIL_HOST');                  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username =  env('MAIL_USERNAME');                  // SMTP username
            $mail->Password = env('MAIL_PASSWORD');                          // SMTP password
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = env('MAIL_PORT');                                    // TCP port to connect to

            // Siapa yang mengirim email
            $mail->setFrom('sifion@b4t.go.id', "Sifion Sertifikasi");

            // Siapa yang akan menerima email
            $mail->addAddress($auditi->email,  $auditi->name);     // Add a recipient
            // $mail->addAddress('ellen@example.com');               // Name is optional

            // ke siapa akan kita balas emailnya
            // $mail->addReplyTo($emailAddress, $name);

            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name


            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = "Pendaftaran Sifion Sertifikasi";
            $mail->Body    = $html;
            $mail->AltBody = $html;

            if ($mail->send()) {
                $section_status_id = SectionStatus::where('transaction_id', $request->transaction_id)->where('section_id', 1)->first()->id;
                $idFormValue = SectionForm::where('section_id', 1)->where('key', 'send_to_email')->first()->id;


                $existing = SectionFormValue::where('section_form_id', $idFormValue)->where('section_status_id', $section_status_id)->first();
                $formValue = (isset($existing->id) && $existing->id) ? $existing : new SectionFormValue();
                $formValue->section_form_id = $idFormValue;
                $formValue->section_status_id = $section_status_id;
                $formValue->value = 1;
                $formValue->save();

                return $this->output(
                    ["status" => true, "data" => "Berhasil Mengirim Email"],
                    "Berhasil Mengirim Email"
                );
            }

            return $this->output(
                ["status" => true, "data" => "Gagal Mengirim Email"],
                "Gagal Mengirim Email"
            );
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }
}
