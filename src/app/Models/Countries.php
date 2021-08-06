<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    /**
     * Table database
     */
    protected $table = 'countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organinamezation_id', 'iso3', 'iso2', 'phonecode', 'capital', 'currency'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'translations', 'timezones', 'latitude', 'longitude', 'emoji', 'emojiU', 'flag', 'wikiDataId'
    ];

    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';

    public function states()
    {
        return $this->belongsTo('App\Models\States', 'id', 'country_id');
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