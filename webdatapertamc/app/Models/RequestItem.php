<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    use HasFactory;

    protected $table = 'request_items';

    protected $fillable = [
        'description',
        'quantity',
        'unit',
        'event_no_io',
        'bidang_req_type',
        'document_number',
        'document_date',
        'project_to_epc_date',
        'pmo_to_epc_date',
        'epc_to_procurement_date',
        'request_type',
        'request_type_doc_number',
        'request_type_doc_date',
        'vendor_name',
        'price',
        'total_price',
        'payment_po_pcm',
        'payment_status',
        'remarks',
        'status',
    ];

    protected $casts = [
        'document_date' => 'date',
        'project_to_epc_date' => 'date',
        'pmo_to_epc_date' => 'date',
        'epc_to_procurement_date' => 'date',
        'request_type_doc_date' => 'date',
        'price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_no_io', 'no_I/O');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_name', 'name');
    }

    // Helper methods
    public function getBidangReqTypeNameAttribute()
    {
        $types = [
            'material_req' => 'Material Request',
            'service_req' => 'Service Request', 
            'facility_req' => 'Facility Request',
            'aset_req' => 'Asset Request'
        ];
        
        return $types[$this->bidang_req_type] ?? $this->bidang_req_type;
    }

    public function getRequestTypeNameAttribute()
    {
        $types = [
            'SPS' => 'Surat Permintaan Spesifikasi',
            'PO' => 'Purchase Order',
            'PCM' => 'Procurement Contract Management'
        ];
        
        return $types[$this->request_type] ?? $this->request_type;
    }

    public function getPaymentStatusNameAttribute()
    {
        $statuses = [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'partial' => 'Partial Payment',
            'cancelled' => 'Cancelled'
        ];
        
        return $statuses[$this->payment_status] ?? $this->payment_status;
    }

    public function getStatusNameAttribute()
    {
        $statuses = [
            'draft' => 'Draft',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    // Workflow progress calculation
    public function getWorkflowProgressAttribute()
    {
        $stages = 0;
        if ($this->project_to_epc_date) $stages++;
        if ($this->pmo_to_epc_date) $stages++;
        if ($this->epc_to_procurement_date) $stages++;
        
        return ($stages / 3) * 100;
    }

    // Check if workflow is complete
    public function isWorkflowCompleteAttribute()
    {
        return $this->project_to_epc_date && $this->pmo_to_epc_date && $this->epc_to_procurement_date;
    }
}
