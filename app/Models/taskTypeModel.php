<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class taskTypeModel extends Model
{
    /**
     * Table database
     */
    protected $table = 'task_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'description', 'status'
    ];

    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';

    public function task()
    {
        return $this->belongsTo('App\Models\taskModel', 'id_programs', 'id');
    }
}