<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        return $this->belongsTo('App\Models\taskModel', 'id_program', 'id');
    }

    public function getResponseAttribute()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'description' => $this->description,
            'status' => $this->status ? 'ACTIVE' : 'NOT_ACTIVE',
            'created_at' =>  Carbon::parse($this->created_at)->toISOString(),
            'updated_at' =>  Carbon::parse($this->updated_at)->toISOString(),

        ];
    }
}