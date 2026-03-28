<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;

class Project extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_IO';
    public $incrementing = false;
    protected $keyType = 'string'; // Karena primary key adalah string

    protected $fillable = [
        'no_IO',
        'title_project',
        'admin_id',
        'assigned_to',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'no_IO';
    }

    /**
     * Relasi One to Many dengan Tools
     */
    public function tools()
    {
        return $this->hasMany(Tool::class, 'no_IO', 'no_IO');
    }

    /**
     * Relasi Many to One dengan Admin (creator)
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'admin_id');
    }

    /**
     * Relasi Many to One dengan Admin (assigned PM)
     */
    public function assignedTo()
    {
        return $this->belongsTo(Admin::class, 'assigned_to', 'admin_id');
    }

    /**
     * Relasi One to One dengan BOQ
     */
    public function boq()
    {
        return $this->hasOne(Boq::class, 'project_no_io', 'no_IO');
    }

    /**
     * Scope untuk filter project berdasarkan assignment untuk PM
     */
    public function scopeAccessibleBy($query, $admin)
    {
        // Jika admin adalah Project Manager, hanya tampilkan project yang di-assign
        if ($admin->role === 'Project Manager') {
            return $query->where('assigned_to', $admin->admin_id);
        }
        
        // Untuk role lain (Admin, VP, etc.), tampilkan semua project
        return $query;
    }

    /**
     * Check if project has BOQ
     */
    public function hasBOQ()
    {
        return $this->boq !== null;
    }

    /**
     * Check if BOQ has content (sections and details)
     */
    public function hasBOQContent()
    {
        if (!$this->hasBOQ()) {
            return false;
        }

        // Check if BOQ has sections
        if (!$this->boq->sections || $this->boq->sections->count() === 0) {
            return false;
        }

        // Check if at least one section has details
        foreach ($this->boq->sections as $section) {
            if ($section->details && $section->details->count() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get BOQ validation status for request button
     */
    public function getBOQValidationStatus()
    {
        if (!$this->hasBOQ()) {
            return [
                'status' => 'no_boq',
                'message' => 'BOQ belum dibuat untuk project ini. Silahkan buat BOQ terlebih dahulu sebelum melakukan request.',
                'action' => 'create_boq'
            ];
        }

        if (!$this->hasBOQContent()) {
            return [
                'status' => 'empty_boq',
                'message' => 'BOQ sudah ada namun masih kosong. Silahkan melakukan pengisian BOQ terlebih dahulu.',
                'action' => 'fill_boq'
            ];
        }

        return [
            'status' => 'valid',
            'message' => 'BOQ valid dan siap untuk request.',
            'action' => 'proceed'
        ];
    }

    /**
     * Check if admin can manage (edit/delete) this project
     */
    public function canBeManaged($admin)
    {
        // Admin dan VP bisa manage semua project
        if (in_array($admin->role, ['Admin', 'VP'])) {
            return true;
        }
        
        // Project Manager hanya bisa manage project yang di-assign
        if ($admin->role === 'Project Manager') {
            return $this->assigned_to === $admin->admin_id;
        }
        
        return false;
    }

    /**
     * Check if admin can access BOQ for this project
     */
    public function canAccessBOQ(Admin $admin)
    {
        // Admin dan VP bisa akses semua BOQ
        if (in_array($admin->role, ['Admin', 'VP'])) {
            return true;
        }
        
        // Project Manager hanya bisa akses BOQ project yang di-assign
        if ($admin->role === 'Project Manager') {
            return $this->assigned_to === $admin->admin_id;
        }
        
        return false;
    }

    /**
     * Check if admin can edit BOQ (considering lock status)
     */
    public function canEditBOQ($admin)
    {
        // Admin dan VP bisa edit semua BOQ
        if (in_array($admin->role, ['Admin', 'VP'])) {
            return true;
        }
        
        // Project Manager hanya bisa edit jika project di-assign dan BOQ belum locked
        if ($admin->role === 'Project Manager') {
            // Harus di-assign ke PM ini
            if ($this->assigned_to !== $admin->admin_id) {
                return false;
            }
            
            // Cek apakah BOQ locked
            $boq = $this->boq;
            if ($boq && $boq->status === 'Locked') {
                return false; // PM tidak bisa edit BOQ yang sudah locked
            }
            
            return true;
        }
        
        return false;
    }
}
