<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\programModel;
use App\Traits\RestApi;

class ProgramController extends Controller
{
    use RestApi;

    public function __construct()
    { }

    public function list()
    {
        $programModel = programModel::get();
        // return $this->output($programModel->toArray());
        return $this->output($programModel->pluck('response'));
    }
}