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
}
