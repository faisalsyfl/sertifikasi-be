<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionStatus extends Model
{
    /**
     * Table database
     */
    protected $table = 'section_status';
    protected $with = ['section:id,name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section_id', 'transaction_id', 'status'
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';

    public function section(){
        return $this->hasOne('App\Models\Section','id','section_id');
    }
    public function scopeFindQuery($query, $keyword = null)
    {
        if (isset($keyword) && $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('key', 'like', "%${keyword}%");
            });
        }
    }
}
