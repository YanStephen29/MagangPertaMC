<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'detail_id',
        'requested_quantity',
        'unit_price',
        'total_price',
        'status',
        'notes'
    ];

    protected $casts = [
        'requested_quantity' => 'integer', // Ubah ke integer
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /**
     * Relationship with Request
     */
    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id', 'id_req');
    }

    /**
     * Relationship with Detail (BOQ item)
     */
    public function detail()
    {
        return $this->belongsTo(Detail::class, 'detail_id', 'no');
    }

    /**
     * Calculate total price automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($requestDetail) {
            $requestDetail->total_price = $requestDetail->requested_quantity * $requestDetail->unit_price;
        });
    }

    /**
     * Get status color for UI
     */
    public function getStatusColor()
    {
        return match($this->status) {
            self::STATUS_APPROVED => 'green',
            self::STATUS_REJECTED => 'red',
            self::STATUS_PENDING => 'yellow',
            default => 'gray'
        };
    }
}
