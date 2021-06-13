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

    public function list($request)
    {
        $validator = Validator::make($request->input(),  Config::get('validation_rules.form_qsc_2.validation_rules'));
        if ($validator->fails()) {
            return ["status" => false, "error" => $validator->errors()->toArray()];
        }
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
            $org = Organization::find($request->organization_id)->with('city','state','country')->first();
            $map  = $this->mapOrg($org->toArray());
        }
        if($request->has('auditi_id')){
            $Auditi = Auditi::find($request->auditi_id)->with('city','state','country')->first();
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
                return ["status" => true, "data" => "Berhasil Menyimpan Data"];
            } catch (\Throwable $th) {
                #save to LOG
            }
        }

        // return ["status" => false, "error" => "No Data!"];
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
        }
    }
}
