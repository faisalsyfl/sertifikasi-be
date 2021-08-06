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
    protected $with = ['auditi:id,name','section_status:id,status,transaction_id,section_id'];
    protected $appends = [
        'sertifikasi',
        'status_sertifikasi',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'code', 'stats' , 'created_at', 'organization_id','auditi_id'
    ];
    protected $hidden = [
        'updated_at','contact_id',
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

    public function section_status()
    {
        return $this->hasMany('App\Models\SectionStatus');
    }

    public function getSertifikasiAttribute() {
        return $this->attributes['sertifikasi'] = 'TBA';
    }
    public function getStatusSertifikasiAttribute() {
        $progress = 0;
        foreach($this->section_status as $temp){
            if($temp['status'] >= 2 ){
                $progress+=1;
            }
        }

        return $this->attributes['status_sertifikasi'] = ''.$progress.' / 7';
    }
    public function setCodeAttribute($type)
    {
        //Type : SC - LSPRO - PC
        $this->attributes['code'] = $this->generateCode($type);
    }
    public function getStatsAttribute($value)
    {
        return ($value) == 1 ? 'ACTIVE' : 'INACTIVE';
    }

    public function getListsAttribute(){
        return $this->attributes['lists'] = '0/7';
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