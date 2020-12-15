<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

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
        'point', 'status', 'like', 'id_task', 'id_user', 'id_angkatan', 'id_program'
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tag_program' => '#' . $this->clean($this->program->name),
            'tag_task' => '#' . $this->clean($this->task->name),
            'approve' => $this->approve,
            'approve' => $this->approve,
            'task' => $this->task,
            'program' => $this->program
        ];
    }

    private function clean($string)
    {
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
}