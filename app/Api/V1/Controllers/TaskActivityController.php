<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\taskActivityModel;
use App\Traits\RestApi;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class TaskActivityController extends Controller
{
    use RestApi;

    public function __construct()
    { }

    public function list()
    {
        $result = taskActivityModel::where('id_angkatan', 1)->where('approve', 2)->orderBy('id', 'DESC')->get();
        return $this->output($result->pluck('List'));
    }

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
        #status 2 = Approve
        $res = $this->getTaskActivity($request->id)->first();
        if ($res && $res->task->point) {
            $point = $res->task->point;

            if (Carbon::now()->greaterThan(Carbon::create($res->task->end_date))) {
                #late task date
                $point = $point / 2;
            }

            $validate = $this->validateRequest($request->all(), $this->rules($point));
            if ($validate)
                return $this->errorRequest(422, 'Validation Error', $validate);

            $res->approve = 2;
            $res->point = $request->point;
            $res->save();

            $res = $this->getTaskActivity($request->id)->orderBy('created_at', 'ASC')->first();
            return $this->output($res->getResponseAttribute(), 'Activity Approved');
        }

        return $this->errorRequest(422, 'Task Activity Not Found');
    }

    public function rejectTaskActivity(Request $request)
    {
        #status 3 = Reject
        $validate = $this->validateRequest($request->all(), ['id' => 'required|numeric|exists:task_activity,id']);
        if ($validate)
            return $this->errorRequest(422, 'Validation Error', $validate);

        $res = $this->getTaskActivity($request->id)->first();
        if ($res) {
            $res->approve = 3;
            $res->save();

            $res = $this->getTaskActivity($request->id)->orderBy('created_at', 'ASC')->get();
            return $this->output($res->pluck('Response')[0], 'Activity Rejected');
        }

        return $this->errorRequest(422, 'Task Activity Not Found');
    }

    private function getTaskActivity($id)
    {
        return taskActivityModel::with(['task', 'program'])->where('id', $id);
    }

    /**
     * Rules configuration method
     * @return array
     */
    private function rules($max = '15')
    {
        return [
            'id' => 'required|numeric|exists:task_activity,id',
            'point' => 'required|numeric|max:' . $max
        ];
    }
}