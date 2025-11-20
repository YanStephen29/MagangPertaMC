<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Section extends Model
{
    protected $table = 'sections';
    
    protected $fillable = [
        'nama',
        'total_harga',
        'boq_nomorBoq'
    ];
    
    protected $casts = [
        'total_harga' => 'integer'
    ];
    
    /**
     * Relationship: Section belongs to BOQ (Many-to-One)
     */
    public function boq(): BelongsTo
    {
        return $this->belongsTo(Boq::class, 'boq_nomorBoq', 'nomorBoq');
    }
    
    /**
     * Relationship: Section has many Details (One-to-Many)
     */
    public function details()
    {
        return $this->hasMany(Detail::class, 'section_id', 'id');
    }
    
    /**
     * Get only root level details (no parent)
     */
    public function rootDetails()
    {
        return $this->hasMany(Detail::class, 'section_id', 'id')->whereNull('parent_no');
    }
    
    /**
     * Accessor to format currency
     */
    public function getFormattedTotalHargaAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }
    
    /**
     * Update total_harga based on details (only root details to avoid double counting)
     */
    public function updateTotalHarga()
    {
        // Only sum root details since they already include children totals
        $total = $this->rootDetails->sum(function ($detail) {
            return $detail->getTotalHargaWithChildren();
        });
        
        $this->update(['total_harga' => $total]);
        
        // Also update BOQ total
        if ($this->boq) {
            $this->boq->updateTotalHarga();
            // Auto-update status if needed
            $this->boq->autoUpdateStatus();
        }
        
        return $total;
    }

    /**
     * Check if admin can edit this section
     */
    public function canBeEdited($admin)
    {
        // Admin dan VP bisa edit semua section
        if (in_array($admin->role, ['Admin', 'Vice President'])) {
            return true;
        }
        
        // Project Manager tidak bisa edit section jika BOQ sudah locked
        if ($admin->role === 'Project Manager') {
            $boq = $this->boq;
            if ($boq && $boq->status === 'Locked') {
                return false;
            }
            
            // Cek apakah project di-assign ke PM ini
            return $boq && $boq->project->assigned_to === $admin->admin_id;
        }
        
        return false;
    }

    /**
     * Check if admin can delete this section
     */
    public function canBeDeleted($admin)
    {
        // Same logic as edit
        return $this->canBeEdited($admin);
    }

    public function getTotalRemainingFunds(){
        return $this->rootDetails->sum(function ($detail) {
            return $detail->getRemainingFunds();
        });
    }
}