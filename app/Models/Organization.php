<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    /**
     * Table database
     */
    protected $table = 'organization';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'npwp', 'type', 'website', 'email', 'telp', 'address', 'city_id', 'state_id', 'country_id', 'postcode'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'city_id', 'state_id', 'country_id'
    ];


    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';

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
                $keyword = '%' . $keyword . '%';
                $q->where('npwp', 'like', $keyword);
                $q->orWhere('name', 'like', $keyword);
            });
        }
    }
}