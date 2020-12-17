<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
            'status' => $this->status ? 'Active' : 'Not Active',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'program' => $this->program->getResponseAttribute(),
            'task_type' => $this->task_type,
        ];
    }
}