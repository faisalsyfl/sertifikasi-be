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
        'id_programs'
    ];

    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';

    public function program()
    {
        return $this->belongsTo('App\Models\programModel');
    }

    public function task_type()
    {
        return $this->hasOne('App\Models\taskTypeModel', 'id', 'id_task_type');
    }
}