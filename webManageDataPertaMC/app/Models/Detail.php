<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    protected $primaryKey = 'no';
    
    protected $fillable = [
        'nama_detail',
        'parent_no',
        'quantity',
        'unit',
        'harga_satuan',
        'harga_total',
        'note',
        'section_id'
    ];
    
    protected $casts = [
        'quantity' => 'decimal:2',
        'harga_satuan' => 'integer',
        'harga_total' => 'integer'
    ];
    
    /**
     * Relationship with Section (many details belong to one section)
     */
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
    
    /**
     * Relationship dengan Tools melalui RequestDetails
     * Detail -> RequestDetails -> Request -> Tool
     */
    public function tools()
    {
        return $this->hasManyThrough(
            Tool::class,             // Final model yang ingin diakses
            RequestDetail::class,    // Intermediate model
            'detail_id',            // Foreign key di request_details yang menunjuk ke details
            'idTools',              // Foreign key di tools (primary key tools)
            'no',                   // Local key di details table
            'request_id'            // Local key di request_details yang menunjuk ke requests
        )->join('tools', 'tools.request_id', '=', 'request_details.request_id')
         ->select('tools.*', 'request_details.requested_quantity', 'request_details.unit_price', 'request_details.total_price', 'request_details.status as request_status');
    }

    /**
     * Alternative method untuk mendapatkan tools yang menggunakan detail ini
     */
    public function getToolsUsingThisDetail()
    {
        return $this->requestDetails()->with(['request.tools'])->get()->map(function($requestDetail) {
            $tool = $requestDetail->request->tools->first();
            if ($tool) {
                $tool->pivot_requested_quantity = $requestDetail->requested_quantity;
                $tool->pivot_unit_price = $requestDetail->unit_price;
                $tool->pivot_total_price = $requestDetail->total_price;
                $tool->pivot_status = $requestDetail->status;
                $tool->pivot_notes = $requestDetail->notes;
            }
            return $tool;
        })->filter();
    }
    
    /**
     * Self-referencing relationship - Parent detail
     */
    public function parent()
    {
        return $this->belongsTo(Detail::class, 'parent_no', 'no');
    }
    
    /**
     * Self-referencing relationship - Child details
     */
    public function children()
    {
        return $this->hasMany(Detail::class, 'parent_no', 'no');
    }
    
    /**
     * Get all descendants (children, grandchildren, etc.)
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Relationship with RequestDetails (one-to-many)
     */
    public function requestDetails()
    {
        return $this->hasMany(RequestDetail::class, 'detail_id', 'no');
    }

    /**
     * Relationship with Requests through RequestDetail (many-to-many)
     */
    public function requests()
    {
        return $this->belongsToMany(Request::class, 'request_details', 'detail_id', 'request_id', 'no', 'id_req')
                    ->withPivot('requested_quantity', 'unit_price', 'total_price', 'status', 'notes')
                    ->withTimestamps();
    }
    
    /**
     * Boot method to automatically calculate harga_total and update section totals
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($detail) {
            // For leaf details (no children), calculate harga_total normally
            if (!$detail->children || $detail->children->count() == 0) {
                $detail->harga_total = $detail->quantity * $detail->harga_satuan;
            }
        });
        
        static::saved(function ($detail) {
            // Update parent totals recursively
            static::updateParentTotals($detail);
            
            // Update section total after detail is saved
            if ($detail->section) {
                $detail->section->updateTotalHarga();
            }
        });
        
        static::deleted(function ($detail) {
            // Update parent totals after deletion
            if ($detail->parent) {
                static::updateParentTotals($detail->parent);
            }
            
            // Update section total after detail is deleted
            if ($detail->section) {
                $detail->section->updateTotalHarga();
            }
        });
    }
    
    /**
     * Update parent totals recursively
     */
    protected static function updateParentTotals($detail)
    {
        if ($detail->parent) {
            $parent = $detail->parent;
            
            // Calculate total from all children
            $totalFromChildren = $parent->children->sum(function ($child) {
                return $child->getTotalHargaWithChildren();
            });
            
            // Update parent's harga_total without triggering saved event
            $parent->timestamps = false;
            $parent->harga_total = $totalFromChildren;
            $parent->save();
            $parent->timestamps = true;
            
            // Recursively update parent's parent
            static::updateParentTotals($parent);
        }
    }
    
    /**
     * Accessor to format currency
     */
    public function getFormattedHargaSatuanAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }
    
    /**
     * Accessor to format currency - uses calculated total from children
     */
    public function getFormattedHargaTotalAttribute()
    {
        return 'Rp ' . number_format($this->getTotalHargaWithChildren(), 0, ',', '.');
    }
    
    /**
     * Get total harga including all children recursively
     */
    public function getTotalHargaWithChildren()
    {
        // If this detail has children, sum all children's totals
        if ($this->children && $this->children->count() > 0) {
            $totalFromChildren = $this->children->sum(function ($child) {
                return $child->getTotalHargaWithChildren();
            });
            return $totalFromChildren;
        }
        
        // If no children, return own harga_total (quantity * harga_satuan)
        return $this->quantity * $this->harga_satuan;
    }
    
    /**
     * Get raw total harga including children (for database updates)
     */
    public function getCalculatedTotalHarga()
    {
        return $this->getTotalHargaWithChildren();
    }
    
    /**
     * Scope to get root level details (no parent)
     */
    public function scopeRootLevel($query)
    {
        return $query->whereNull('parent_no');
    }
    
    /**
     * Scope to get details for a specific section
     */
    public function scopeForSection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }
    
    /**
     * Check if this detail is a descendant of another detail
     */
    public function isDescendantOf(Detail $potentialAncestor)
    {
        $current = $this->parent;
        
        while ($current) {
            if ($current->no === $potentialAncestor->no) {
                return true;
            }
            $current = $current->parent;
        }
        
        return false;
    }
    
    /**
     * Get the depth level of this detail in the hierarchy
     */
    public function getDepth()
    {
        $depth = 0;
        $current = $this->parent;
        
        while ($current) {
            $depth++;
            $current = $current->parent;
        }
        
        return $depth;
    }

    /**
     * Check if admin can edit this detail
     */
    public function canBeEdited($admin)
    {
        // Admin dan VP bisa edit semua detail
        if (in_array($admin->role, ['Admin', 'Vice President'])) {
            return true;
        }
        
        // Project Manager tidak bisa edit detail jika BOQ sudah locked
        if ($admin->role === 'Project Manager') {
            $boq = $this->section->boq;
            if ($boq && $boq->status === 'Locked') {
                return false;
            }
            
            // Cek apakah project di-assign ke PM ini
            return $boq && $boq->project->assigned_to === $admin->admin_id;
        }
        
        return false;
    }

    /**
     * Check if admin can delete this detail
     */
    public function canBeDeleted($admin)
    {
        // Same logic as edit
        return $this->canBeEdited($admin);
    }

    /**
     * Get available quantity (total - used by active requests)
     */
    public function getAvailableQuantity()
    {
        $usedQuantity = $this->getActualUsedQuantity();
        return max(0, $this->quantity - $usedQuantity);
    }

    /**
     * Get used quantity by status
     */
    public function getUsageByStatus()
    {
        $requestDetails = $this->requestDetails()->with('request')->get();
        
        return [
            'approved' => $requestDetails->where('request.status_req', 'Approved')->sum('requested_quantity'),
            'pending' => $requestDetails->where('request.status_req', 'Pending')->sum('requested_quantity'),
            'hold' => $requestDetails->where('request.status_req', 'Hold')->sum('requested_quantity'),
            'on_process' => $requestDetails->where('request.status_req', 'On Process')->sum('requested_quantity'),
            'rejected' => $requestDetails->where('request.status_req', 'Rejected')->sum('requested_quantity'),
        ];
    }

    /**
     * Get optimized used quantity based on priority (highest quantity first)
     * This method considers the rule that details with highest quantity should be used first
     */
    public function getOptimizedUsedQuantity()
    {
        // Get all details in the same section, ordered by quantity DESC
        $sectionDetails = $this->section->details()
            ->whereNull('parent_no') // Only root details
            ->orderBy('quantity', 'DESC')
            ->get();
        
        // Get total used quantity for all active requests in this section
        $totalSectionUsed = 0;
        foreach($sectionDetails as $detail) {
            $totalSectionUsed += $detail->requestDetails()
                ->whereHas('request', function($q) {
                    $q->whereIn('status_req', ['Approved', 'Pending', 'Hold', 'On Process']);
                })
                ->sum('requested_quantity');
        }
        
        // Distribute usage starting from highest quantity details
        $remainingUsage = $totalSectionUsed;
        $currentDetailUsed = 0;
        
        foreach($sectionDetails as $detail) {
            if ($detail->no === $this->no) {
                // This is our current detail
                $currentDetailUsed = min($remainingUsage, $detail->quantity);
                break;
            }
            
            // Subtract this detail's capacity from remaining usage
            $remainingUsage = max(0, $remainingUsage - $detail->quantity);
        }
        
        return $currentDetailUsed;
    }

    /**
     * Get actual used quantity from request_details (current implementation)
     */
    public function getActualUsedQuantity()
    {
        $usedQuantity = $this->requestDetails()
            ->whereHas('request', function($q) {
                // Fix case sensitivity - gunakan status yang sebenarnya ada di database
                $q->whereIn('status_req', ['Approved', 'Pending', 'Hold', 'On Process']);
            })
            ->sum('requested_quantity');
            
        // Debug logging (can be removed in production)
        \Log::debug('getActualUsedQuantity Debug', [
            'detail_id' => $this->no,
            'detail_name' => $this->nama_detail,
            'used_quantity' => $usedQuantity,
            'request_details_count' => $this->requestDetails()->count(),
            'active_request_details' => $this->requestDetails()
                ->whereHas('request', function($q) {
                    $q->whereIn('status_req', ['Approved', 'Pending', 'Hold', 'On Process']);
                })
                ->count()
        ]);
        
        return $usedQuantity;
    }

    /**
     * Get usage summary
     */
    public function getUsageSummary()
    {
        $usageByStatus = $this->getUsageByStatus();
        $totalUsed = $usageByStatus['approved'] + $usageByStatus['pending'] + $usageByStatus['hold'] + $usageByStatus['on_process'];
        $available = $this->quantity - $totalUsed;
        
        return [
            'total_quantity' => $this->quantity,
            'used_quantity' => $totalUsed,
            'available_quantity' => max(0, $available),
            'usage_by_status' => $usageByStatus,
            'utilization_percentage' => $this->quantity > 0 ? round(($totalUsed / $this->quantity) * 100, 2) : 0
        ];
    }

    public function getRemainingFunds()
    {
        if ($this->children && $this->children->count() > 0) {
            return $this->children->sum(function ($child) {
                return $child->getRemainingFunds();
            });
        }
        $usedQty = $this->getActualUsedQuantity();
        $remainingQty = $this->quantity - $usedQty;
        return $remainingQty * $this->harga_satuan;
    }
}
