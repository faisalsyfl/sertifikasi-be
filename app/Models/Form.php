<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helper\Helper;
use Illuminate\Support\Facades\DB;


class Form extends Model
{
    /**
     * Table database
     */
    protected $table = 'form';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = [
        'id'
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
                $q->orWhere('code', 'like', $keyword);
            });
            $query->orWhereHas('organization', function ($qr) use ($keyword) {
                $keyword = '%' . $keyword . '%';
                $qr->where('name', 'like', $keyword);
                $qr->orWhere('email', 'like', $keyword);
            });
        }
    }
}
