<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class document extends Model
{
    use HasFactory;

    protected $table = 'documents';
    protected $primaryKey = 'no_request';
    public $incrementing = false;
    protected $keyType = 'string';

    public const JENIS_REQUEST = [
        'Material Request', 
        'Service Request', 
        'Facility Request',
        'Aset'
    ];

    protected $fillable = [
        'no_request',
        'jenis_request',
        'date_issue',
    ];

    public function tools()
    {
        return $this->hasMany(tools::class, 'Document_no_request', 'no_request');
    }
}