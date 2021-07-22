<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionFormValue extends Model
{
    /**
     * Table database
     */
    protected $table = 'section_form_value';
    protected $with = ['sectionForm:id,key'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section_status_id', 'value', 'frozen_value'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'reference_table', 'reference_id', 'section_form_id'
    ];

    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';
    public function sectionForm()
    {
        return $this->hasOne('App\Models\SectionForm', 'id', 'section_form_id');
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
