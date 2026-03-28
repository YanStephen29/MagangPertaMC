<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Boq extends Model
{
    protected $table = 'boqs';
    
    // Set primary key
    protected $primaryKey = 'nomorBoq';
    
    // Primary key is not auto-incrementing
    public $incrementing = false;
    
    // Primary key is string
    protected $keyType = 'string';
    
    protected $fillable = [
        'nomorBoq',
        'total_harga',
        'date',
        'project_no_io',
        'status'
    ];
    
    protected $casts = [
        'date' => 'date',
        'total_harga' => 'integer'
    ];
    
    /**
     * Relationship: BOQ belongs to Project (One-to-One)
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_no_io', 'no_IO');
    }
    
    /**
     * Relationship: BOQ has many Sections (One-to-Many)
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'boq_nomorBoq', 'nomorBoq');
    }
    
    /**
     * Update total_harga based on sections
     */
    public function updateTotalHarga()
    {
        $total = $this->sections()->sum('total_harga');
        $this->update(['total_harga' => $total]);
        return $total;
    }
    
    /**
     * Accessor to format currency
     */
    public function getFormattedTotalHargaAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }
    
    /**
     * Check if BOQ can be locked
     */
    public function canBeLocked()
    {
        // BOQ must have sections
        if ($this->sections->count() === 0) {
            return false;
        }
        
        // Check if all details have required data
        foreach ($this->sections as $section) {
            if (!$this->sectionHasCompleteDetails($section)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Check if section has complete details (all details must have quantity, unit price, and total)
     */
    private function sectionHasCompleteDetails($section)
    {
        // Get all details (including nested children)
        $allDetails = $this->getAllDetailsFromSection($section);
        
        foreach ($allDetails as $detail) {
            // Only check leaf details (details without children)
            if ($detail->children->count() === 0) {
                if (empty($detail->quantity) || empty($detail->harga_satuan) || empty($detail->unit)) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Get all details from a section recursively
     */
    public function getAllDetailsFromSection($section)
    {
        $allDetails = collect();
        
        foreach ($section->details as $detail) {
            $allDetails->push($detail);
            $allDetails = $allDetails->merge($this->getChildrenRecursive($detail));
        }
        
        return $allDetails;
    }
    
    /**
     * Get children recursively
     */
    public function getChildrenRecursive($detail)
    {
        $children = collect();
        
        foreach ($detail->children as $child) {
            $children->push($child);
            $children = $children->merge($this->getChildrenRecursive($child));
        }
        
        return $children;
    }
    
    /**
     * Update BOQ status to Open when sections or details are added
     */
    public function updateStatusToOpen()
    {
        // Reload sections relationship to get fresh data
        $this->load('sections');
        
        if ($this->status === 'Pending' && $this->sections->count() > 0) {
            $this->update(['status' => 'Open']);
        }
    }
    
    /**
     * Auto-check and update status based on current state
     */
    public function autoUpdateStatus()
    {
        $this->load('sections');
        
        if ($this->status === 'Pending' && $this->sections->count() > 0) {
            $this->update(['status' => 'Open']);
            return true;
        }
        
        return false;
    }
    
    /**
     * Lock the BOQ if it can be locked
     */
    public function lockBoq()
    {
        if ($this->canBeLocked() && $this->status !== 'Locked') {
            $this->update(['status' => 'Locked']);
            return true;
        }
        return false;
    }
    
    /**
     * Get status badge class for styling
     */
    public function getStatusBadgeClass()
    {
        switch ($this->status) {
            case 'Pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'Open':
                return 'bg-blue-100 text-blue-800';
            case 'Locked':
                return 'bg-green-100 text-green-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    /**
     * Check if admin can edit this BOQ
     */
    public function canBeEdited($admin)
    {
        // Admin dan VP bisa edit semua BOQ
        if (in_array($admin->role, ['Admin', 'Vice President'])) {
            return true;
        }
        
        // Project Manager tidak bisa edit BOQ yang sudah locked
        if ($admin->role === 'Project Manager' && $this->status === 'Locked') {
            return false;
        }
        
        // Cek apakah project di-assign ke PM ini
        if ($admin->role === 'Project Manager') {
            return $this->project->assigned_to === $admin->admin_id;
        }
        
        return false;
    }

    /**
     * Check if admin can delete this BOQ
     */
    public function canBeDeleted($admin)
    {
        // Same logic as edit - PM tidak bisa delete BOQ yang locked
        return $this->canBeEdited($admin);
    }
}
