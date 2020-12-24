<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use App\Models\taskActivityModel;
use Illuminate\Support\Facades\DB;

class taskModel extends Model
{
    /**
     * Table database
     */
    protected $table = 'task';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'role', 'status', 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id_program', 'id_task_type', 'order', 'document'
    ];

    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';

    public function program()
    {
        return $this->hasOne('App\Models\programModel', 'id', 'id_program');
    }

    public function task_type()
    {
        return $this->hasOne('App\Models\taskTypeModel', 'id', 'id_task_type');
    }

    public function task_activity()
    {
        return $this->belongsTo('App\Models\taskActivityModel', 'id', 'id_task');
    }

    public function getResponseAttribute()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'point' => $this->point,
            'icon' => $this->icon,
            'description' => $this->description,
            'image' => $this->image,
            'status' => $this->status ? 'ACTIVE' : 'NOT_ACTIVE',
            'start_date' => Carbon::parse($this->start_date)->toISOString(),
            'end_date' => Carbon::parse($this->end_date)->toISOString(),
            'task_open' => $this->dateChecker($this->start_date, $this->end_date),
            'task_late' => Carbon::now()->greaterThan($this->end_date),
            'task_status_progress' => $this->checkProgress($this->id),
            'created_at' => Carbon::parse($this->created_at)->toISOString(),
            'updated_at' => Carbon::parse($this->updated_at)->toISOString(),
            'deleted_at' => Carbon::parse($this->deleted_at)->toISOString(),
            'task_type' => $this->task_type->getResponseAttribute(),
            'program' => $this->program,
        ];
    }

    private function dateChecker($startDate, $endDate)
    {
        $first = Carbon::create($startDate);
        $second = Carbon::create($endDate);
        return Carbon::now()->between($first, $second);
    }

    private function checkProgress($taskId)
    {
        $user = Auth::user();
        // $a = taskActivityModel::where('id_task', $taskId)->where('id_user', $user->id)->first();
        // $a = DB::table('task_activity')->where('id_task', $taskId)->first();
        $res = taskActivityModel::where('id_task', $taskId)->where('id_user', $user->id)->first();

        $status = 'MISSION_ON_PROGRESS';
        if ($res) {
            switch ($res->approve) {
                case 1:
                    $status = 'MISSION_IN_REVIEW';
                    break;
                case 2:
                    $status = 'MISSION_APPROVED';
                    break;
                case 3:
                    $status = 'MISSION_REJECTED';
                    break;
            }
        }

        return $status;
    }
}