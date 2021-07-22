<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormLocation extends Model
{
    /**
     * Table database
     */
    protected $table = 'form_location';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_type', 'address', 'location', 'country_id', 'state_id', 'city_id', 'postcode'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function state()
    {
        return $this->hasOne('App\Models\States', 'id', 'state_id');
    }

    public function country()
    {
        return $this->hasOne('App\Models\Countries', 'id', 'country_id');
    }

    public function city()
    {
        return $this->hasOne('App\Models\Cities', 'id', 'city_id');
    }


    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';

    public function scopeFindQuery($query, $keyword = null)
    {
        if (isset($keyword) && $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('address', 'like', "%${keyword}%");
                $q->orWhere('location', 'like', "%${keyword}%");
            });
        }
    }
}
