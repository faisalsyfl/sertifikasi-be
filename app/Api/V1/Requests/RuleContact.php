<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class RuleContact extends FormRequest
{
    public function rules()
    {
        return Config::get('validation_rules.form_contact.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}