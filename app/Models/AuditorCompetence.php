<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditorCompetence extends Model
{
    /**
     * Table database
     */
    protected $table = 'auditor_competence';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'auditor_id', 'competence_id',
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';
}
