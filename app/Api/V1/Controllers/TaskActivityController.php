<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\taskActivityModel;
use App\Traits\RestApi;
use Illuminate\Http\Request;
use Auth;

class TaskActivityController extends Controller
{
    use RestApi;

    public function __construct()
    { }

    public function mateTaskList(Request $request)
    {
        $user = Auth::user();
        if ($user->id) {

            $res = taskActivityModel::whereIn('id_user', function ($q) use ($user) {
                $q->from('commers_has_mate')
                    ->selectRaw('id_user')
                    ->where('id_mate', $user->id);
            })->where('status', 1)->skip($request->start)->take($request->length)->orderBy('created_at', 'ASC')->get();

            return $this->output($res->pluck('Response'));
        }

        return $this->errorRequest(422, 'User Not Found');
    }

    public function approveTaskActivity(Request $request)
    {
        $res = taskActivityModel::with(['task'])->where('id', $request->id)->first();
        if ($res->task->point) {

            $validate = $this->validateRequest($request->all(), $this->rules($res->task->point));

            if ($validate)
                return $this->errorRequest(422, 'Validation Error', $validate);

            $res = taskActivityModel::with(['task', 'program'])->where('id', $request->id)->first();
            $res->approve = 1;
            $res->save();

            $res = taskActivityModel::skip($request->start)->take($request->length)->orderBy('created_at', 'ASC')->get();
            return $this->output($res->pluck('Response'), 'Activity Approved');
        }

        return $this->errorRequest(422, 'Task Point Not Found');
    }

    /**
     * Rules configuration method
     * @return array
     */
    private function rules($max = '15')
    {
        return [
            'id' => 'required|numeric|exists:task_activity,id',
            'point' => 'required|numeric|max:' . $max,
            'start' => 'required|numeric',
            'length' => 'required|numeric'
        ];
    }
}