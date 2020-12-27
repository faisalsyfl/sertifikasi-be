<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mailTokenModel extends Model
{
    /**
     * Table database
     */
    protected $table = 'mail_token';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 'email','active_token','wrong'
    ];
    protected $hidden = [
        'created_at','updated_at','id'
    ];


    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';
}