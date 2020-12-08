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
}