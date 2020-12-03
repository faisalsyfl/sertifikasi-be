<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'point', 'status', 'like', 'id_task', 'id_user'
    ];

    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';

    // public function task()
    // {
    //     return $this->belongsTo('App\Models\taskModel');
    // }
}