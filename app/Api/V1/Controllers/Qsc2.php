<?php

namespace App\Api\V1\Controllers;

use Validator, Config;
use App\Http\Controllers\Controller;
use App\Traits\RestApi;

class Qsc2 extends Controller
{
    use RestApi;

    public function store($request)
    {
        $validator = Validator::make($request->input(),  Config::get('validation_rules.form_qsc_2.validation_rules'));
        if ($validator->fails()) {
            return ["status" => false, "error" => $validator->errors()->toArray()];
        }

        dd($request->all());
    }
}
