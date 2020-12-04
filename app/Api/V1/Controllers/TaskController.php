<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\taskModel;
use App\Models\programModel;
use App\Traits\RestApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    use RestApi;

    public function __construct()
    { }

    public function list(Request $request)
    {
        if (isset($request->id_program) && $request->id_program) {
            if (programModel::where('id', $request->id_program)->count() != 0) {
                $taskModel = taskModel::with(['task_type'])->where('id_program', $request->id_program)->get();
                return $this->output($taskModel);
            }
            return $this->errorRequest(422, 'Id Program Not Found');
        }
    }

    public function detail(Request $request)
    {
        if (isset($request->id_task) && $request->id_task) {
            $taskModel = taskModel::with(['task_type', 'program'])->where('id', $request->id_task)->first();
            return $this->output($taskModel);
        }
        return $this->errorRequest(422, 'Task Not Found');
    }
}