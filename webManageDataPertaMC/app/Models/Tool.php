<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tool extends Model
{
    protected $primaryKey = 'idTools';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Description',
        'quantity',
        'unit',
        'delivery_date',
        'remarks',
        'no_IO',
        'kode_GL',
        'no_document'
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'quantity' => 'integer'
    ];

    protected $dates = [
        'delivery_date'
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'idTools';
    }

    /**
     * Relasi Many to One dengan Project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'no_IO', 'no_IO');
    }

    /**
     * Relasi One to One dengan Bidang
     */
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'kode_GL', 'kode_GL');
    }

    /**
     * Relasi Many to One dengan Document
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'no_document', 'no_request');
    }

    /**
     * Relasi One to One dengan Request
     */
    public function request()
    {
        return $this->hasOne(Request::class, 'tool_id', 'idTools');
    }
}
