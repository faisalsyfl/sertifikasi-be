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

    public function updateStatusTask(RuleTaskFinish $request)
    {
        $review = $request->review ? $request->review : '';
        $id_task = $request->id_task;

        $taskActivity = taskActivityModel::where('id_task', $request->id_task);
        if ($taskActivity->count() == 0) {
            $user = Auth::user();
            $data = [
                'id_user' => $user->id,
                'id_task' => $id_task,
                'id_angkatan' => 1,
                'status' => 1
            ];
            $taskActivity = taskActivityModel::create($data);
            $taskActivityGet = taskActivityModel::with(['task', 'user'])->where('id', $taskActivity->id)->first();
            return $this->output($taskActivityGet);
        } else {
            $activity = taskActivityModel::with(['task'])->where('id_task', $id_task)->first();
            $activity->review = $review;
            $activity->save();

            #save attachment
            $this->saveAttachment($request, $id_task);

            $taskActivityGet = taskActivityModel::with(['task', 'user', 'attachment'])->where('id_task', $id_task)->first();
            return $this->output($taskActivityGet);
        }
        return $this->errorRequest(422, 'Task Not Found');
    }

    private function saveAttachment($request, $id_task)
    {
        if ($request->file('file')) {
            $file = $request->file('file');

            $file_hash = 'attachment_' . $this->hash_filename($file->getClientOriginalName());
            $file_info['file_type']     = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $file_info['file_hash']     = $file_hash . "." . $file->getClientOriginalName();
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
            } else {
                $retAttach = attachmentModel::create($file_info);

                $saveTask = taskActivityModel::where('id_task', $id_task)->first();
                $saveTask->id_attachment = $retAttach->id;
                $saveTask->save();
            }

            return true;
        }

        return false;
    }

    public function getTask($id_task)
    {
        return taskModel::with(['task_type', 'program'])->where('id', $id_task)->first();
    }

    private function updateRespone($task)
    {
        return $task ?
            $this->output($task, 'Status Updated', 200) : $this->output($task, 'Status Update Fail', 422);
    }
}