<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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

    public function getResponseAttribute()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status ? 'ACTIVE' : 'NOT_ACTIVE',
            'description' => $this->description,
            'created_at' => Carbon::parse($this->created_at)->toISOString(),
            'updated_at' => Carbon::parse($this->updated_at)->toISOString(),
            'task' => $this->task->pluck('response'),
        ];
    }
}