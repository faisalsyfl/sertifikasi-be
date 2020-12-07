<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class RuleTaskFinish extends FormRequest
{
    public function rules()
    {
        return Config::get('validation_rules.task_finish.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}