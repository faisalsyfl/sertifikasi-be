<?php

namespace App\Api\V1\Controllers;

use Validator, Config;
use App\Http\Controllers\Controller;
use App\Traits\RestApi;
use App\Models\SectionForm;

class Qsc2 extends Controller
{
    use RestApi;

    public function store($request)
    {
        # Merge Rule Validation
        $field = SectionForm::where('section_id', $request['section'])->whereNotNull('rule')->get()->toArray();
        $arrayRule = [];
        foreach ($field as $v) {
            $arrayRule[$v['key']] = $v['rule'];
        }

        $arrayRule = array_merge($arrayRule, Config::get('validation_rules.form_qsc_2.validation_rules'));

        $validator = Validator::make($request->input(),   $arrayRule);
        if ($validator->fails()) {
            return ["status" => false, "error" => $validator->errors()->toArray()];
        }

        dd($request->all());
    }
}
