<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function rules()
    {
        return Config::get('validation_rules.update_profile.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}