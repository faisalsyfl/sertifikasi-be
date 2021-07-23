<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class RuleAuditi extends FormRequest
{
    public function rules()
    {
        return Config::get('validation_rules.form_auditi.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}