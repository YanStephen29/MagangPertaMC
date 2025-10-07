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
        'project_no_io'
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
}
