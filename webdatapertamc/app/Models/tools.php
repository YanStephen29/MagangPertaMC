<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tools extends Model
{
    use HasFactory;

    protected $table = 'tools';
    protected $primaryKey = 'idTools';


    protected $fillable = [
        'description',
        'quantity',
        'unit',
        'deliveryDate',
        'remarks',
        'event_no_I/O',
        'Document_no_request',
        'Bidang_kodeGl',
    ];

    public function bidang()
    {
        return $this->belongsTo(bidang::class, 'Bidang_kodeGl', 'kodeGl');
    }

    public function event()
    {
        return $this->belongsTo(event::class, 'event_no_I/O', 'no_I/O');
    }

    public function document()
    {
        return $this->belongsTo(document::class, 'Document_no_request', 'no_request');
    }
}
