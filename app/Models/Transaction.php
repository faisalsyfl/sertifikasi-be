<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helper\Helper;
use Illuminate\Support\Facades\DB;


class Transaction extends Model
{
    /**
     * Table database
     */
    protected $table = 'transaction';
    protected $with = ['organization'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'code', 'organization_id', 'auditi_id', 'contact_id', 'status', 'stats'
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
        return $this->hasOne('App\Models\organization', 'id', 'organization_id');
    }

    public function section_status()
    {
        return $this->hasMany('App\Models\SectionStatus', 'id', 'transaction_id');
    }

    public function setCodeAttribute($type)
    {
        //Type : SC - LSPRO - PC
        $this->attributes['code'] = $this->generateCode($type);
    }
    public function getStatusAttribute($value)
    {
        return $this->status = statusConvert($value);
    }

    private function generateCode($type)
    {
        if ($type == 'SC') {
            $latest = DB::table('transaction')->where('code', 'like', 'SC%')->latest()->first();
            if ($latest == null)
                return 'SC-' . '00001';

            $latest = (int) explode('-', $latest->code)[1];
            $latest += 1;
            return 'SC-' . substr(str_repeat(0, 5) . $latest, -5);
        } else {
            // LSPRO Condition below
            // PC Condition below
        }
    }
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
