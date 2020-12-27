<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\taskActivityModel;
use App\Models\taskModel;
use Auth;

class programModel extends Model
{
    use SoftDeletes;

    /**
     * Table database
     */
    protected $table = 'programs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'order', 'status', 'description'
    ];

    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';

    public function task()
    {
        return $this->hasMany('App\Models\taskModel', 'id_program', 'id');
    }

    public function getProgramAttribute()
    {
        $progress =  $this->callProgress($this->id);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status ? 'ACTIVE' : 'NOT_ACTIVE',
            'description' => $this->description,
            'created_at' => Carbon::parse($this->created_at)->toISOString(),
            'updated_at' => Carbon::parse($this->updated_at)->toISOString(),
            'total_task' => $progress->total_task,
            'total_finish_task' => $progress->total_finish_task,
            'progress' => $progress->progress,
            'task' => $this->task->pluck('response'),
        ];
    }

    private function callProgress($id)
    {
        $progressMission =
            [
                "total_task" => 0,
                "total_finish_task" => 0,
                "progress" => 0
            ];

        $user = Auth::user();
        $totalTask = taskModel::where('id_program', $id)->count();
        $totalTaskActivity = taskActivityModel::where('id_program', $id)->where('id_user', $user->id)->where('approve', 2)->count();

        $progressMission['total_task'] = $totalTask;
        $progressMission['total_finish_task'] = $totalTaskActivity;
        $progressMission['progress'] = $totalTaskActivity == 0 || $totalTask == 0 ? 0 : (int) (($totalTaskActivity / $totalTask) * 100);

        return (object) $progressMission;
    }
}