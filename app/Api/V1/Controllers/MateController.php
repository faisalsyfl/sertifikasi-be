<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\taskActivityModel;
use App\Traits\RestApi;
use Illuminate\Http\Request;
use Auth;
use Config;

class MateController extends Controller
{
    use RestApi;

    public function __construct()
    { }

    public function mateTaskList(Request $request)
    {
        $user = Auth::user();
        $limit = $request->limit ? $request->limit : 10;

        if ($user->id) {

            $validate = $this->validateRequest($request->all(), Config::get('validation_rules.mate_list_commers.validation_rules'));
            if ($validate)
                return $this->errorRequest(422, 'Validation Error', $validate);

            $activityModel = taskActivityModel::whereIn('id_user', function ($q) use ($user) {
                $q->from('commers_has_mate')
                    ->selectRaw('id_user')
                    ->where('id_mate', $user->id);
            })->where('status', statusConvert($request->status))
                ->orderBy('created_at', 'ASC');

            $activityModel = $activityModel->paginate($limit);
            $taskArray = $activityModel->toArray();

            //get only Pagination Param
            $this->pagination = array_except($taskArray, 'data');

            return $this->output($activityModel->pluck('Response'));
        }

        return $this->errorRequest(422, 'User Not Found');
    }
}