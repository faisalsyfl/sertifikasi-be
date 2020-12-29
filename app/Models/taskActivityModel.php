<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
// use App\Models\taskModel;
use Illuminate\Support\Facades\DB;

class taskActivityModel extends Model
{
    /**
     * Table database
     */
    protected $table = 'task_activity';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'point', 'status', 'like', 'id_task', 'id_user', 'id_angkatan', 'id_program', 'point'
    ];

    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';

    public function task()
    {
        return $this->hasOne('App\Models\taskModel', 'id', 'id_task');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'id_user');
    }

    public function attachment()
    {
        return $this->hasOne('App\Models\attachmentModel', 'id', 'id_attachment');
    }

    public function angkatan()
    {
        return $this->hasOne('App\Models\angkatanModel', 'id', 'id_angkatan');
    }

    public function program()
    {
        return $this->hasOne('App\Models\programModel', 'id', 'id_program');
    }

    public function getResponseAttribute()
    {
        return [
            'id' => $this->id,
            'review' => $this->review,
            'status' => $this->status,
            'created_at' => Carbon::parse($this->created_at)->toISOString(),
            'updated_at' => Carbon::parse($this->updated_at)->toISOString(),
            'tag_program' => $this->program->tag_program,
            'tag_task' => $this->task->tag_task,
            'approve' => $this->approve,
            'point' => $this->point,
            'task' => $this->task->getResponseAttribute()
        ];
    }

    public function getListAttribute()
    {
        return [
            'id' => $this->id,
            'review' => $this->review,
            'created_at' => Carbon::parse($this->created_at)->toISOString(),
            'updated_at' => Carbon::parse($this->updated_at)->toISOString(),
            'task_status_progress' => app('App\Models\taskModel')->checkProgress($this->id_task),
            'tag_program' => $this->program->tag_program,
            'tag_task' => $this->task->tag_task,
            'attachment' => $this->attachment,
            'task' => $this->task->getResponseAttribute()
        ];
    }

    public function scopeFindQuery($query, $keyword = null)
    {
        if (isset($keyword) && $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->orWhereHas('program', function ($qr) use ($keyword) {
                    $qr->where('tag_program', 'like', '%' . $keyword . '%');
                });
                $q->orWhereHas('task', function ($qr) use ($keyword) {
                    $qr->where('tag_task', 'like', '%' . $keyword . '%');
                });
                $q->orWhereHas('task', function ($qr) use ($keyword) {
                    $qr->Where('description', 'like', '%' . $keyword . '%');
                });
            });
        }
    }
}