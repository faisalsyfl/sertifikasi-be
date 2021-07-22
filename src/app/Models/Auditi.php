<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditi extends Model
{
    /**
     * Table database
     */
    protected $table = 'auditi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id', 'name', 'type', 'website', 'email', 'telp', 'address', 'city_id', 'state_id', 'country_id', 'postcode'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];


    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';

    public function organization()
    {
        return $this->hasOne('App\Models\Organization', 'id', 'organization_id');
    }

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

    public function scopeFindQuery($query, $keyword = null)
    {
        if (isset($keyword) && $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('name', 'like', "%${keyword}%");
            });
        }
    }
}