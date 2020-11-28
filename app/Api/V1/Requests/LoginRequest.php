<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return Config::get('validation_rules.login.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
