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
        }
        
        return $total;
    }
}
