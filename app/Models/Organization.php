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
        'name', 'npwp', 'type', 'website', 'email', 'telp', 'address', 'city_id', 'province_id', 'postcode'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
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
                $keyword = '%' . $keyword . '%';
                $q->where('npwp', 'like', $keyword);
                $q->orWhere('name', 'like', $keyword);
            });
        }
    }
}
