<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    use HasFactory;

    protected $table = 'request_types';

    protected $fillable = [
        'code',
        'name',
        'description',
        'document_prefix',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper methods
    public function generateDocumentNumber($sequence = null)
    {
        $sequence = $sequence ?: $this->getNextSequence();
        $year = date('Y');
        $month = date('m');
        
        return sprintf('%s-%s%s-%04d', $this->document_prefix, $year, $month, $sequence);
    }

    private function getNextSequence()
    {
        $year = date('Y');
        $month = date('m');
        $prefix = $this->document_prefix . '-' . $year . $month;
        
        $lastDoc = \DB::table('request_items')
            ->where('request_type', $this->code)
            ->where('request_type_doc_number', 'like', $prefix . '%')
            ->orderBy('request_type_doc_number', 'desc')
            ->first();
            
        if (!$lastDoc) {
            return 1;
        }
        
        $lastNumber = intval(substr($lastDoc->request_type_doc_number, -4));
        return $lastNumber + 1;
    }
}
