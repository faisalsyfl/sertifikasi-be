<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class RuleQSC2 extends FormRequest
{
    public function rules()
    {
        return Config::get('validation_rules.form_qsc_2.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
