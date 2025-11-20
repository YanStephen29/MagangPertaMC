<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $primaryKey = 'no_request';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_request',
        'jenis_request',
        'date_issue',
        'description',
    ];

    protected $casts = [
        'date_issue' => 'date',
    ];

    protected $appends = [
        'status_dokumen',
    ];

    // Define enum values for validation
    public const JENIS_REQUEST_OPTIONS = [
        'Material Request',
        'Service Request', 
        'Facility Request',
        'Aset'
    ];

    // Relationship with tools (one document can have many tools)
    public function tools()
    {
        return $this->hasMany(Tool::class, 'no_document', 'no_request');
    }

    // Relationship with tahapans (one document can have many tahapans)
    public function tahapans()
    {
        return $this->hasMany(Tahapan::class, 'no_request', 'no_request');
    }

    // Get the first request through tools (updated for new relationship)
    public function request()
    {
        return $this->hasOneThrough(Request::class, Tool::class, 'no_document', 'id_req', 'no_request', 'request_id');
    }

    // Get current tahapan (latest active or most recently completed)
    public function getCurrentTahapan()
    {
        // Define tahapan order
        $tahapanOrder = [
            'BELUM DI PROSES' => 1,
            'PROJECT TO EPC (EPC PROCESS)' => 2,
            'PMO TO EPC' => 3,
            'EPC TO PROCUREMENT' => 4
        ];

        // Get all tahapans for this document
        $allTahapans = $this->tahapans()->get();
        
        if ($allTahapans->isEmpty()) {
            return null;
        }

        // Find the highest completed tahapan
        $completedTahapans = $allTahapans->filter(function($tahapan) {
            return $tahapan->Date_Tahapan !== null;
        });

        if ($completedTahapans->isEmpty()) {
            // No tahapan completed, return BELUM DI PROSES if exists
            return $allTahapans->firstWhere('namaTahapan', 'BELUM DI PROSES');
        }

        // Get the highest order completed tahapan
        $highestCompleted = $completedTahapans->sortByDesc(function($tahapan) use ($tahapanOrder) {
            return $tahapanOrder[$tahapan->namaTahapan] ?? 0;
        })->first();

        // If all 4 tahapans are completed, return the last one
        if ($completedTahapans->count() == 4) {
            return $highestCompleted;
        }

        // Otherwise, find the next tahapan after the highest completed one
        $nextOrder = ($tahapanOrder[$highestCompleted->namaTahapan] ?? 0) + 1;
        $nextTahapanName = array_search($nextOrder, $tahapanOrder);
        
        if ($nextTahapanName) {
            $nextTahapan = $allTahapans->firstWhere('namaTahapan', $nextTahapanName);
            if ($nextTahapan) {
                return $nextTahapan;
            }
        }

        // Fallback: return the highest completed tahapan
        return $highestCompleted;
    }

    // Get tahapan progress percentage based on current status
    public function getProgressPercentage()
    {
        $currentStatus = $this->status_dokumen;
        
        return match($currentStatus) {
            'BELUM DI PROSES' => 0,
            'PROJECT TO EPC (EPC PROCESS)' => 33.33,
            'PMO TO EPC' => 66.66,
            'EPC TO PROCUREMENT' => 100,
            default => 0
        };
    }

    // Get current document status based on highest completed tahapan
    public function getStatusDokumenAttribute()
    {
        // Define tahapan order
        $tahapanOrder = [
            'BELUM DI PROSES' => 1,
            'PROJECT TO EPC (EPC PROCESS)' => 2,
            'PMO TO EPC' => 3,
            'EPC TO PROCUREMENT' => 4
        ];

        // Get all completed tahapans for this document
        $completedTahapans = $this->tahapans()->whereNotNull('Date_Tahapan')->get();
        
        if ($completedTahapans->isEmpty()) {
            return 'BELUM DI PROSES';
        }

        // Get the highest order completed tahapan
        $highestCompleted = $completedTahapans->sortByDesc(function($tahapan) use ($tahapanOrder) {
            return $tahapanOrder[$tahapan->namaTahapan] ?? 0;
        })->first();

        return $highestCompleted->namaTahapan ?? 'BELUM DI PROSES';
    }

    // Helper method to get route key name
    public function getRouteKeyName()
    {
        return 'no_request';
    }


}
