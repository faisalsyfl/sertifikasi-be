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

class Qsc4 extends Controller
{
    use RestApi;

    public function list($request, $id)
    {
        $section = 4;
        $section_status_id = SectionStatus::where('transaction_id', $id)->where('section_id', $section)->first();
        $existing = [];
        if ($section_status_id) {
            $existing = SectionFormValue::where('section_status_id', $section_status_id->id)->get();
            if (count($existing) > 0) {
                $existing = $existing->toArray();
            }
        } else {
            return ["status" => false, "data" => "Gagal Mendapatkan Detail Form Step 4"];
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
                    $request_data = $request->all();

                    $penawaran = isset($request_data['penawaran']) ? $request_data['penawaran'] : 0;
                    $biaya_sertifikasi = isset($request_data['biaya_sertifikasi'])
                        ? $request_data['biaya_sertifikasi'] : 0;
                    $transportasi = isset($request_data['transportasi'])
                        ? $request_data['transportasi'] : 0;

                    if(!isset($request_data['total'])){
                        $request_data['total'] = 0;
                        $request_data['total'] += $penawaran;
                        $request_data['total'] += $biaya_sertifikasi;
                        $request_data['total'] += $transportasi;
                    }

                    if(!isset($request_data['nilai_penawaran'])){
                        $request_data['nilai_penawaran'] = $request_data['total'];
                    }
                    if(!isset($request_data['terbilang'])){
                        $request_data['terbilang'] = $this->getTerbilang($request_data['total']) . "Rupiah";
                    }

                    foreach ($request_data as $key => $v) {
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

                    $this->generateData($section_status, [
                        "total" => $request_data["total"],
                        "biaya_sertifikasi" => $biaya_sertifikasi,
                        "transportasi" => $transportasi,
                    ]);

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
     * @OA\Put(
     *  path="/api/v1/qsc4/set-payment/{id}",
     *  summary="Update payment status",
     *  tags={"Payment"},
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="Payment id",
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="status",
     *      in="query",
     *      required=true,
     *      description="1 untuk melunasi pembayaran",
     *      @OA\Schema(
     *           type="integer",
     *           default="1",
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

    public function setPaymentStatus(Request $request, $payment_id){
        $status = $request->has('status') ? $request->status : null;

        if($status != null){
            $payment = Payment::find($payment_id);
            if($payment){
                $transaction = Transaction::find($payment->transaction_id);
                $auditi = null;
                if($transaction){
                    $auditi = Auditi::find($transaction->auditi_id);
                }
                $payment->status = $status;
                if($status == 1 and $transaction and $auditi){
                    $payment->status = 1;
                    $payment->payment_datetime = Carbon::now()->format('Y-m-d H:i:s');

                    $payment->receipt = URL::to('/')."/".PdfController::generateKwitansi([
                        "tanggal_invoice" => $payment->created_at,
                        "transaction_id" => $payment->transaction_id,
                        "va_number" => $payment->payment_code,
                        "nama_klien" => $auditi->name,
                        "alamat_klien" => $auditi->address,
                        "total" => $auditi->amount,
                        "jenis" => self::getJenisSertifikasiManajemen($transaction->id),
                        "sertifikasi" => self::getJenisSertifikasi($transaction->id),
                    ], "file_path");
                }elseif ($status == 0){
                    $payment->payment_datetime = null;
                }else{
                    return ["status" => false, "data" => "Invalid status"];
                }
                $payment->save();

                return $this->output(
                    ["status" => true, "data" => "Berhasil Menyimpan Data"],
                    "Berhasil Menyimpan Data"
                );
            }else{
                return $this->output(
                    ["status" => false, "data" => "Payment tidak ditemukan"],
                    "Payment tidak ditemukan"
                );
            }
        }else{
            return $this->output(
                ["status" => false, "data" => "Invalid status"],
                "Invalid status"
            );
        }
    }

    /**
     * @OA\Post(
     *  path="/api/v1/qsc4/email-payment/{id}",
     *  summary="Send email payment document",
     *  tags={"Payment"},
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="Payment id",
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      description="Target email",
     *      @OA\Schema(
     *           type="string",
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="document",
     *      in="query",
     *      required=true,
     *      description="Document type (offering, invoice, receipt)",
     *      @OA\Schema(
     *           type="string",
     *           default="offering",
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

    public function sendPaymentEmail(Request $request, $payment_id){
        $email = $request->has('email') ? $request->email : null;
        $document = $request->has('document') ? $request->document : null;

        if($email and $document){
            $emails = explode(";", $email);

            $payment = Payment::find($payment_id);
            if($payment){
                $transaction = Transaction::find($payment->transaction_id);
                $auditi = null;
                if($transaction){
                    $auditi = Auditi::find($transaction->auditi_id);
                }

                if($transaction and $auditi){
                    $attachment = null;

                    if($document == "offering"){
                        $attachment = $payment->other_documents;
                    }elseif ($document == "invoice"){
                        $attachment = $payment->invoice;
                    }elseif ($document == "receipt"){
                        $attachment = $payment->receipt;
                    }

                    MailController::setupMail([
                        "name" => $auditi->name,
                        "email" => $emails,
                        "attachment" => $attachment
                    ]);

                    return $this->output(
                        ["status" => true, "data" => "Berhasil Mengirim Email"],
                        "Berhasil Mengirim Email"
                    );
                }else{
                    return $this->output(
                        ["status" => false, "data" => "Transaksi tidak ditemukan"],
                        "Transaksi tidak ditemukan"
                    );
                }
            }else{
                return $this->output(
                    ["status" => false, "data" => "Payment tidak ditemukan"],
                    "Payment tidak ditemukan"
                );
            }
        }else{
            return $this->output(
                ["status" => false, "data" => "Email tidak ditemukan"],
                "Email tidak ditemukan"
            );
        }
    }

    private function generateOfferingPayment($transaction_id, $data=[]){
        $transaction = Transaction::find($transaction_id);
        $payment = null;

        if($transaction){
            $auditi = Auditi::find($transaction->auditi_id);
            $payment = Payment::where("transaction_id",$transaction->id)
                ->where("type","penawaran")->first();
            $update = false;
            if(!$payment and $auditi) {
                $payment = new Payment();
                $update = true;
            } else if($payment and $auditi and $payment->amount != $data["total"]){
                $update = true;
            }

            if($update){
                $payment->transaction_id = $transaction->id;
                $payment->type = "penawaran";
                $payment->amount = isset($data["total"]) ? $data["total"] : 0;
                $payment->method = "VA";
                $payment->status = 0;

                // TODO: Random VA for now, use BNI dummy VA later
                $digits = 10;
                $va_number = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
                $payment->payment_code = $va_number;
                $payment->payment_expiration = Carbon::now()->addDays(3)->format('Y-m-d H:i:s');

                $payment->invoice = URL::to('/')."/".PdfController::generateInvoice([
                        "biaya_sertifikasi" => isset($data["biaya_sertifikasi"]) ? $data["biaya_sertifikasi"] : 0,
                        "transportasi" => isset($data["transportasi"]) ? $data["transportasi"] : 0,
                        "transaction_id" => $transaction->id,
                        "tanggal_invoice" => Carbon::now()->format('Y-m-d H:i:s'),
                        "nama_klien" => $auditi->name,
                        "alamat_klien" => $auditi->address,
                        "va_number" => $va_number,
                    ], "file_path");
                $payment->other_documents = URL::to('/')."/".PdfController::generatePenawaran([
                        "biaya_sertifikasi" => isset($data["biaya_sertifikasi"]) ? $data["biaya_sertifikasi"] : 0,
                        "transportasi" => isset($data["transportasi"]) ? $data["transportasi"] : 0,
                        "transaction_id" => $transaction->id,
                        "tanggal_invoice" => Carbon::now()->format('Y-m-d H:i:s'),
                        "tanggal_expire" => Carbon::now()->addDays(3)->format('Y-m-d H:i:s'),
                        "nama_klien" => $auditi->name,
                        "alamat_klien" => $auditi->address.", ".self::getFullAddress(1),
                        "va_number" => $va_number,
                        "jenis" => self::getJenisSertifikasiManajemen($transaction_id),
                        "jenis_lengkap" => self::getJenisSertifikasiManajemen($transaction_id, true),
                        "sertifikasi" => self::getJenisSertifikasi($transaction->id),
                    ], "file_path");

                $payment->save();
            }
        }

        return $payment;
    }

    private function generateData($section_status, $data=[]){
        $section_forms = SectionForm::where('section_id', $section_status->section_id)
            ->whereIn('key', ['payment'])->get();

        foreach ($section_forms as $section_form){
            $value = "";

            if($section_form->key == "payment" and isset($data["total"])){
                $payment = $this->generateOfferingPayment($section_status->transaction_id, $data);
                if($payment){
                    $value = $payment->id;
                }
            }

            if($value){
                $form_value = SectionFormValue::where('section_form_id', $section_form->id)
                    ->where('section_status_id', $section_status->id)->first();
                if(!$form_value){
                    $form_value = new SectionFormValue();
                }
                $form_value->section_form_id = $section_form->id;
                $form_value->section_status_id =  $section_status->id;
                $form_value->value = $value;
                $form_value->save();
            }
        }
    }

    private function getTerbilang($nilai){
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";

        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = $this->getTerbilang($nilai - 10). " belas";
        } else if ($nilai < 100) {
            $temp = $this->getTerbilang($nilai/10)." puluh". $this->getTerbilang($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . $this->getTerbilang($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->getTerbilang($nilai/100) . " ratus" . $this->getTerbilang($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . $this->getTerbilang($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->getTerbilang($nilai/1000) . " ribu" . $this->getTerbilang($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->getTerbilang($nilai/1000000) . " juta" . $this->getTerbilang($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->getTerbilang($nilai/1000000000) . " milyar" . $this->getTerbilang(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = $this->getTerbilang($nilai/1000000000000) . " trilyun" . $this->getTerbilang(fmod($nilai,1000000000000));
        }

        return ucwords($temp);
    }

    static function getKeyValueQSC4()
    {
        return [
            "penawaran" => 0,
            "biaya_sertifikasi" => 0,
            "transportasi" => 0,
            "terbilang" => "-",
            "total" => 0,
            "nama_klien" => "-",
            "nomor_sertifikasi" => "-",
            "nomor_registrasi" => "-",
            "payment" => null,
        ];
    }

    static function get_payment_object($payment_id, $transaction_id=null, $public=false)
    {
        if($transaction_id){
            $payment = Payment::where('transaction_id',$transaction_id)->first();
        }else{
            $payment = Payment::find($payment_id);
        }

        if ($payment) {
            $result = $payment->toArray();
            $result["offering_value"] = $result["amount"];
            $result["offering_form"] = $result["other_documents"];

            unset($result["amount"]);
            unset($result["other_documents"]);
            unset($result["created_at"]);
            unset($result["updated_at"]);

            if($public){
                $keeps = ["offering_value", "offering_form", "invoice", "receipt"];
                $result = array_intersect_key($result, array_flip($keeps));
            }

            return $result;
        } else {
            return (object) [];
        }
    }

    static function getJenisSertifikasiManajemen($transaction_id, $complete=false){
        $sertifikasi_management = [];
        $section_status = SectionStatus::where("transaction_id",$transaction_id)
            ->where("section_id",2)->first();
        $section_form = SectionForm::whereIn("key",["manajemen_mutu","manajemen_lingkungan","manajemen_keselamatan"])
            ->where("section_id",2)->get();

        if($section_status and $section_form){
            foreach ($section_form as $item){
                $section_value = SectionFormValue::where("section_form_id",$item->id)
                    ->where("section_status_id",$section_status->id)->first();

                if($section_value and $item->key == "manajemen_mutu" and $section_value->value){
                    array_push($sertifikasi_management,($complete ? "Manajemen Mutu " : "") . "ISO 9001:2015");
                } elseif ($section_value and $item->key == "manajemen_lingkungan" and $section_value->value){
                    array_push($sertifikasi_management,($complete ? "Manajemen Lingkungan " : "") . "ISO 14001:2015");
                } elseif ($section_value and $item->key == "manajemen_keselamatan" and $section_value->value){
                    array_push($sertifikasi_management,($complete ? "Manajemen Keselamatan " : "") . "ISO 45001:2018");
                }
            }
        }

        return implode(", ",$sertifikasi_management);
    }

    static function getJenisSertifikasi($transaction_id){
        $sertifikasi = "";
        $section_status = SectionStatus::where("transaction_id",$transaction_id)
            ->where("section_id",2)->first();
        $section_form = SectionForm::where("key","status_aplikasi_sertifikasi")
            ->where("section_id",2)->first();

        if($section_status and $section_form){
            $section_value = SectionFormValue::where("section_form_id",$section_form->id)
                ->where("section_status_id",$section_status->id)->first();

            if($section_value){
                if($section_value->value == "SERTIFIKASI_AWAL"){
                    $sertifikasi = "Sertifikasi Awal";
                } elseif ($section_value->value == "RESERTIFIKASI"){
                    $sertifikasi = "Resertifikasi";
                }
            }
        }

        return $sertifikasi;
    }

    static function getFullAddress($transaction_id){
        $address = [
            "kota" => "",
            "provinsi" => "",
            "kode_pos" => "",
        ];
        $section_status = SectionStatus::where("transaction_id",$transaction_id)
            ->where("section_id",1)->first();
        $section_form = SectionForm::whereIn("key",["kota","provinsi","kode_pos"])
            ->where("section_id",1)->get();

        if($section_status and $section_form){
            foreach ($section_form as $item){
                $section_value = SectionFormValue::where("section_form_id",$item->id)
                    ->where("section_status_id",$section_status->id)->first();

                if($section_value and $item->key == "kota" and $section_value->value and $section_value->value != "-"){
                    $address["kota"] = $section_value->value;
                } elseif ($section_value and $item->key == "provinsi" and $section_value->value and $section_value->value != "-"){
                    $address["provinsi"] = $section_value->value;
                } elseif ($section_value and $item->key == "kode_pos" and $section_value->value and $section_value->value != "-"){
                    $address["kode_pos"] = $section_value->value;
                }
            }
        }

        return implode(", ",$address);
    }
}
