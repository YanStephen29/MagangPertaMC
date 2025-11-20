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
        'no_document',
        'request_id',
        'status_tools',
        'approved_by',
        'approved_at',
        'approval_notes',
        'rejected_by',
        'rejected_at',
        'rejection_notes'
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'quantity' => 'integer',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected $dates = [
        'delivery_date'
    ];

    // Define enum values for status_tools
    public const STATUS_TOOLS_OPTIONS = [
        'On Process',
        'Hold', 
        'Rejected',
        'Closed'
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
     * Relasi Many to One dengan Request
     */
    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id', 'id_req');
    }

    /**
     * Relationship dengan RequestDetails melalui Request
     * Tool -> Request -> RequestDetails
     */
    public function requestDetails()
    {
        if ($this->request) {
            return $this->request->requestDetails(); // Eloquent Builder
        }
        // Return Eloquent Builder kosong
        return \App\Models\RequestDetail::query()->whereRaw('0 = 1');
    }

    public function details()
    {
        if (!$this->request) {
            return $this->newQuery()->whereRaw('0 = 1'); // Return empty collection if no request
        }
        
        return $this->hasManyThrough(
            Detail::class,           // Final model yang ingin diakses
            RequestDetail::class,    // Intermediate model
            'request_id',           // Foreign key di request_details table yang menunjuk ke requests
            'no',                   // Foreign key di details table (primary key details)
            'request_id',           // Local key di tools table (request_id)
            'detail_id'             // Local key di request_details yang menunjuk ke details
        )->select('details.*', 'request_details.requested_quantity', 'request_details.unit_price', 'request_details.total_price', 'request_details.status as request_status');
    }

    /**
     * Alternative method untuk mendapatkan details dengan pivot data yang lebih jelas
     */
    public function getDetailsWithRequestInfo()
    {
        if (!$this->request) {
            return collect();
        }
        
        return $this->request->requestDetails()->with('detail')->get()->map(function($requestDetail) {
            $detail = $requestDetail->detail;
            $detail->pivot_requested_quantity = $requestDetail->requested_quantity;
            $detail->pivot_unit_price = $requestDetail->unit_price;
            $detail->pivot_total_price = $requestDetail->total_price;
            $detail->pivot_status = $requestDetail->status;
            $detail->pivot_notes = $requestDetail->notes;
            return $detail;
        });
    }

    /**
     * Relationship with Admin who approved this tool
     */
    public function approvedBy()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'approved_by', 'admin_id');
    }

    /**
     * Relationship with Admin who rejected this tool
     */
    public function rejectedBy()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'rejected_by', 'admin_id');
    }

    /**
     * Get color class for status_tools
     */
    public function getStatusToolsColor()
    {
        return match($this->status_tools) {
            'On Process' => 'blue',
            'Hold' => 'yellow',
            'Rejected' => 'red',
            'Closed' => 'green',
            default => 'gray'
        };
    }

    /**
     * Get display text for status_tools with proper formatting
     */
    public function getStatusToolsDisplay()
    {
        return match($this->status_tools) {
            'On Process' => 'On Process',
            'Hold' => 'Hold',
            'Rejected' => 'Rejected',
            'Closed' => 'Closed',
            default => $this->status_tools
        };
    }

    /**
     * Check if tool needs approval (status is Hold)
     */
    public function needsApproval()
    {
        return $this->status_tools === 'Hold';
    }

    /**
     * Approve tool by admin
     */
    public function approve($adminId, $notes = null)
    {
        $this->update([
            'status_tools' => 'On Process',
            'approved_by' => $adminId,
            'approved_at' => now(),
            'approval_notes' => $notes,
            'rejected_by' => null,
            'rejected_at' => null,
            'rejection_notes' => null
        ]);
    }

    /**
     * Reject tool by admin
     */
    public function reject($adminId, $notes = null)
    {
        $this->update([
            'status_tools' => 'Rejected',
            'rejected_by' => $adminId,
            'rejected_at' => now(),
            'rejection_notes' => $notes,
            'approved_by' => null,
            'approved_at' => null,
            'approval_notes' => null
        ]);
    }
}
