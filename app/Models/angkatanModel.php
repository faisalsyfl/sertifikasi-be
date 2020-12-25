<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class angkatanModel extends Model
{
    /**
     * Table database
     */
    protected $table = 'angkatan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'tahun'
    ];
    protected $hidden = [
        'created_at','updated_at',
    ];


    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';
}