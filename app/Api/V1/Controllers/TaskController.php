<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\taskModel;
use App\Models\programModel;
use App\Models\taskActivityModel;
use App\Models\attachmentModel;
use App\Traits\RestApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Api\V1\Requests\RuleTaskFinish;
use Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    use RestApi;

    public function __construct()
    { }

    public function list(Request $request)
    {
        $limit = isset($request->limit) ? $request->limit : 10;
        if (isset($request->id_program) && $request->id_program) {
            if (programModel::where('id', $request->id_program)->count() != 0) {
                $taskModel = taskModel::with(['program'])->where('id_program', $request->id_program)->orderBy('id', 'ASC');

                $taskModel = $taskModel->paginate($limit);
                $taskArray = $taskModel->toArray();

                //get only Pagination Param
                $this->pagination = array_except($taskArray, 'data');
                return $this->output($taskModel->pluck('response'), "Data Found");
            }
        }
        return $this->errorRequest(422, 'ID Program Not Found');
    }

    public function detail(Request $request)
    {
        if (isset($request->id_task) && $request->id_task) {
            $taskModel = taskModel::where('id', $request->id_task)->first()->getResponseAttribute();
            return $this->output($taskModel);
        }
        return $this->errorRequest(422, 'Task Not Found');
    }

    public function updateStatusTask(RuleTaskFinish $request)
    {
        $review = $request->review ? $request->review : '';
        $id_task = $request->id_task;
        $id_program = $this->getTask($id_task) ? $this->getTask($id_task)->id_program : '';

        if (!$id_program)
            return $this->errorRequest(422, 'Task Not Found');

        $user = Auth::user();
        $taskActivity = taskActivityModel::where('id_task', $request->id_task)->where('id_user', $user->id);
        if ($taskActivity->count() == 0) {
            $taskActivityRes = $this->createTaskActivity($id_task, $id_program, $user);
            $this->saveReviewAttachment($request, $id_task, $review, $user);

            $taskActivityGet = $this->getListTaskActivity($taskActivityRes->id);
            return $this->output($taskActivityGet);
        } else {

            $this->saveReviewAttachment($request, $id_task, $review, $user);

            $taskActivityGet = $this->getListTaskActivity($taskActivity->first()->id);
            return $this->output($taskActivityGet);
        }
        return $this->errorRequest(422, 'Task Not Found');
    }

    private function createTaskActivity($id_task, $id_program, $user)
    {
        $data = [
            'id_user' => $user->id,
            'id_task' => $id_task,
            'id_angkatan' => $user->id_angkatan,
            'id_program' =>  $id_program,
            'status' => 1
        ];
        return taskActivityModel::create($data);
    }

    private function saveAttachment($request, $id_task)
    {
        if ($request->file('file')) {
            $file = $request->file('file');

            $file_hash = 'attachment_' . $this->hash_filename();
            $file_info['file_type']     = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $file_info['file_hash']     = $file_hash . "_" . $file->getClientOriginalName();
            $file_info['file_ori']      = pathinfo($file->getClientOriginalName(), PATHINFO_BASENAME);
            $file_info['file_name']     = $file->getClientOriginalName();
            $file_info['file_size']     = $file->getSize();
            $file_info['id_parent']     = $id_task;
            $file_info['type']          = 'task';

            Storage::disk('s3')->put('attachment/' . $file_hash, file_get_contents($file));
            $attach = attachmentModel::where('id_parent', $id_task);

            if ($attach->count() > 0) {
                DB::table('attachment')
                    ->where('id_parent', $id_task)
                    ->update($file_info);

                $id_attachment = $attach->first() ? $attach->first()->id : '';
                $this->getTaskActivity((int) $id_task, $id_attachment);
            } else {
                $retAttach = attachmentModel::create($file_info);
                $this->getTaskActivity((int) $id_task, $retAttach->id);
            }

            return true;
        }

        return false;
    }

    private function saveReviewAttachment($request, $id_task, $review, $user)
    {
        $activity = taskActivityModel::with(['task'])->where('id_task', $id_task)->where('id_user', $user->id)->first();
        $activity->review = $review;
        $activity->approve = 1;
        $activity->point = 0;
        $activity->save();

        #save attachment
        $this->saveAttachment($request, $id_task);
    }

    private function getTaskActivity($id_task, $id_attachment)
    {
        $saveTask = taskActivityModel::where('id_task', $id_task)->first();
        $saveTask->id_attachment = $id_attachment;
        $saveTask->save();
    }

    public function getTask($id_task)
    {
        return taskModel::with(['task_type', 'program'])->where('id', $id_task)->first();
    }

    public function getListTaskActivity($id)
    {
        return taskActivityModel::with(['task', 'user', 'attachment', 'angkatan', 'program'])->where('id', $id)->first();
    }
}