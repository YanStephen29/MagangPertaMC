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
        'status_req',
        'tool_id'
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
        'Closed',
        'On Proses'
    ];

    // Relationship with Tool (many-to-one) 
    public function tool()
    {
        return $this->belongsTo(Tool::class, 'tool_id', 'idTools');
    }



    // Helper methods for getting color classes based on status
    public function getStatusColor()
    {
        return match($this->status_req) {
            'Closed' => 'green',
            'On Proses' => 'yellow',
            default => 'gray'
        };
    }


}
