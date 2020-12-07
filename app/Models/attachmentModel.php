<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class attachmentModel extends Model
{
    /**
     * Table database
     */
    protected $table = 'attachment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'file_name', 'file_hash', 'file_ori', 'file_type', 'file_size', 'id_parent'
    ];

    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';

    public function task_activity()
    {
        return $this->hasOne('App\Models\taskActivityModel', 'id', 'id_parent');
    }
}