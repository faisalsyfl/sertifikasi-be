<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    /**
     * Table database
     */
    protected $table = 'competence';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'code', 'risk'
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';

    public function scopeFindQuery($query, $keyword = null)
    {
        if (isset($keyword) && $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('type', 'like', "%${keyword}%")
                    ->orWhere('code', 'like', "%${keyword}%")
                    ->orWhere('name', 'like', "%${keyword}%");
            });
        }
    }
}
