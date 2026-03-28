<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'contact_person',
        'vendor_type',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function requestItems()
    {
        return $this->hasMany(RequestItem::class, 'vendor_name', 'name');
    }

    // Helper methods
    public function getVendorTypeNameAttribute()
    {
        $types = [
            'supplier' => 'Supplier',
            'contractor' => 'Contractor',
            'service_provider' => 'Service Provider',
            'other' => 'Other'
        ];
        
        return $types[$this->vendor_type] ?? $this->vendor_type;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('vendor_type', $type);
    }
}
