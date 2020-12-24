<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    // public function like()
    // {
    //     return $this->hasOne('App\User', 'id', 'id_user');
    // }

    // public function hashtag()
    // {
    //     return $this->hasOne('App\User', 'id', 'id_user');
    // }

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

    private function clean($string)
    {
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
}