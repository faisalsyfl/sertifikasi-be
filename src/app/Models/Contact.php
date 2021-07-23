<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * Table database
     */
    protected $table = 'contact';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'email', 'telp', 'jabatan', 'auditi_id'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'auditi_id'
    ];


    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';

    public function auditi()
    {
        return $this->hasOne('App\Models\Auditi', 'id', 'auditi_id');
    }

    public function scopeFindQuery($query, $keyword = null)
    {
        if (isset($keyword) && $keyword) {
            $query->where(function ($q) use ($keyword) {
                $keyword = '%' . $keyword . '%';
                $q->where('name', 'like', $keyword);
            });
        }
    }
}