<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class commentModel extends Model
{
    /**
     * Table database
     */
    protected $table = 'comment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comment', 'id_user'
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
}