<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionFormValue extends Model
{
    /**
     * Table database
     */
    protected $table = 'section_form_value';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section_status_id', 'section_form_id', 'reference_table', 'reference_id', 'value', 'frozen_value'
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
                $q->orWhere('key', 'like', "%${keyword}%");
            });
        }
    }
}
