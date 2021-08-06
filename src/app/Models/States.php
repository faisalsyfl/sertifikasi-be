<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    /**
     * Table database
     */
    protected $table = 'states';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'country_code', 'iso2'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];


    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';

    public function country()
    {
        return $this->hasOne('App\Models\Countries', 'id', 'country_id');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\Cities', 'id', 'state_id');
    }

    public function scopeFindQuery($query, $keyword = null)
    {
        if (isset($keyword) && $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('name', 'like', "%${keyword}%");
            });
        }
    }
}