<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_req';
    
    protected $fillable = [
        'type_surat',
        'jenis_req', 
        'no_surat',
        'date_req',
        'status_req'
    ];

    protected $casts = [
        'date_req' => 'date',
    ];

    // Define enum values for validation
    public const TYPE_SURAT_OPTIONS = [
        'SPS',
        'SPMP', 
        'PCM'
    ];

    public const JENIS_REQ_OPTIONS = [
        'PO',
        'Kontrak',
        'PCM'
    ];

    public const STATUS_REQ_OPTIONS = [
        'Pending',
        'On Process',
        'Closed'
    ];

    // Relationship with Tools (one-to-many) 
    public function tools()
    {
        return $this->hasMany(Tool::class, 'request_id', 'id_req');
    }

    // Helper method for backward compatibility - returns first tool
    public function tool()
    {
        return $this->tools()->first();
    }

    // Relationship with RequestDetails (one-to-many)
    public function requestDetails()
    {
        return $this->hasMany(\App\Models\RequestDetail::class, 'request_id', 'id_req');
    }

    // Relationship with Details through RequestDetail (many-to-many)
    public function details()
    {
        return $this->belongsToMany(Detail::class, 'request_details', 'request_id', 'detail_id', 'id_req', 'no')
                    ->withPivot('requested_quantity', 'unit_price', 'total_price', 'status', 'notes')
                    ->withTimestamps();
    }



    // Helper methods for getting color classes based on status
    public function getStatusColor()
    {
        return match($this->status_req) {
            'Pending' => 'yellow',
            'On Process' => 'blue',
            'Closed' => 'green',
            default => 'gray'
        };
    }
    
    /**
     * Get display text for status with proper formatting
     */
    public function getStatusDisplay()
    {
        return match($this->status_req) {
            'Pending' => 'Pending',
            'On Process' => 'On Process',
            'Closed' => 'Closed',
            default => $this->status_req
        };
    }


}