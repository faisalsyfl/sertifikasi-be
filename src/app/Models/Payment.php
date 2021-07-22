<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * Table database
     */
    protected $table = 'payment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id', 'type', 'amount','method', 'payment_code',
        'status', 'invoice', 'receipt', 'other_documents', 'payment_expiration'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'payment_datetime'
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
                $q->orWhere('name', 'like', "%${keyword}%");
            });
        }
    }
}
