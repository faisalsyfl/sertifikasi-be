<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormDocumentStatus extends Model
{
    /**
     * Table database
     */
    protected $table = 'form_document_status';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id', 'section_id', 'status', 'type', 'created_by'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];


    /**
     * Primary Key
     * @var string
     */
    protected $primaryKey = 'id';
}