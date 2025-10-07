<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Authenticatable
{
    protected $primaryKey = 'admin_id';
    
    protected $fillable = [
        'username',
        'password',
        'role',
        'privilege'
    ];

    protected $casts = [
        'privilege' => 'array',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Define role constants
    public const ROLES = [
        'Admin',
        'VP', 
        'Manager Construction',
        'Project Manager',
        'Project Control',
        'Cost Control',
        'User'
    ];

    // Define privilege types - Granular Permission System
    public const PRIVILEGES = [
        // Project Management
        'project_view' => 'Project - View',
        'project_add' => 'Project - Add', 
        'project_edit' => 'Project - Edit',
        'project_delete' => 'Project - Delete',
        'project_assign' => 'Project - Assign Project',
        
        // Tools Management  
        'tools_view' => 'Tools - View',
        'tools_add' => 'Tools - Add',
        'tools_edit' => 'Tools - Edit',
        'tools_delete' => 'Tools - Delete',
        
        // Kode Bidang Management
        'kode_bidang_view' => 'Kode Bidang - View',
        'kode_bidang_add' => 'Kode Bidang - Add', 
        'kode_bidang_edit' => 'Kode Bidang - Edit',
        'kode_bidang_delete' => 'Kode Bidang - Delete',
        
        // Document Management
        'document_view' => 'Document - View',
        'document_add' => 'Document - Add',
        'document_edit' => 'Document - Edit',
        'document_delete' => 'Document - Delete',
        
        // BOQ Management
        'boq_view' => 'BOQ - View', 
        'boq_add' => 'BOQ - Add',
        'boq_edit' => 'BOQ - Edit',
        'boq_delete' => 'BOQ - Delete',
        'boq_assign' => 'BOQ - Assign',
        
        // BOQ Section Management
        'boq_section_view' => 'BOQ Section - View',
        'boq_section_add' => 'BOQ Section - Add',
        'boq_section_edit' => 'BOQ Section - Edit',
        'boq_section_delete' => 'BOQ Section - Delete',
        
        // BOQ Detail Management
        'boq_detail_view' => 'BOQ Detail - View',
        'boq_detail_add' => 'BOQ Detail - Add', 
        'boq_detail_edit' => 'BOQ Detail - Edit',
        'boq_detail_delete' => 'BOQ Detail - Delete',
        
        // Account Management
        'account_view' => 'Account - View',
        'account_add' => 'Account - Add',
        'account_edit' => 'Account - Edit',
        'account_delete' => 'Account - Delete',
        
        // Legacy privileges for backward compatibility
        'full_access' => 'Full System Access',
        'manage_admin' => 'Manage Admin Users',
        'view_reports' => 'View Reports'
    ];

    // Privilege groups for better organization
    public const PRIVILEGE_GROUPS = [
        'project' => [
            'label' => 'Project Management',
            'privileges' => [
                'project_view',
                'project_add', 
                'project_edit',
                'project_delete',
                'project_assign'
            ]
        ],
        'tools' => [
            'label' => 'Tools Management',
            'privileges' => [
                'tools_view',
                'tools_add',
                'tools_edit', 
                'tools_delete'
            ]
        ],
        'kode_bidang' => [
            'label' => 'Kode Bidang Management',
            'privileges' => [
                'kode_bidang_view',
                'kode_bidang_add',
                'kode_bidang_edit',
                'kode_bidang_delete'
            ]
        ],
        'document' => [
            'label' => 'Document Management', 
            'privileges' => [
                'document_view',
                'document_add',
                'document_edit',
                'document_delete'
            ]
        ],
        'boq' => [
            'label' => 'BOQ Management',
            'privileges' => [
                'boq_view',
                'boq_add',
                'boq_edit',
                'boq_delete',
                'boq_assign'
            ]
        ],
        'boq_section' => [
            'label' => 'BOQ Section Management',
            'privileges' => [
                'boq_section_view',
                'boq_section_add', 
                'boq_section_edit',
                'boq_section_delete'
            ]
        ],
        'boq_detail' => [
            'label' => 'BOQ Detail Management',
            'privileges' => [
                'boq_detail_view',
                'boq_detail_add',
                'boq_detail_edit', 
                'boq_detail_delete'
            ]
        ],
        'account' => [
            'label' => 'Account Management',
            'privileges' => [
                'account_view',
                'account_add',
                'account_edit',
                'account_delete'
            ]
        ]
    ];

    /**
     * Relationship with projects (one admin to many projects)
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'admin_id', 'admin_id');
    }

    /**
     * Check if admin has specific privilege
     */
    public function hasPrivilege(string $privilege): bool
    {
        // Admin role automatically has all privileges
        if ($this->role === 'Admin') {
            return true;
        }
        
        return in_array($privilege, $this->privilege ?? []);
    }

    /**
     * Check if admin has any of the given privileges
     */
    public function hasAnyPrivilege(array $privileges): bool
    {
        // Admin role automatically has all privileges
        if ($this->role === 'Admin') {
            return true;
        }
        
        return !empty(array_intersect($privileges, $this->privilege ?? []));
    }

    /**
     * Get all privileges for this admin
     */
    public function getAllPrivileges(): array
    {
        return $this->privilege ?? [];
    }

    /**
     * Check CRUD privilege for a specific feature
     */
    public function canCreate(string $feature): bool
    {
        return $this->hasPrivilege($feature . '_create');
    }

    public function canRead(string $feature): bool
    {
        return $this->hasPrivilege($feature . '_read');
    }

    public function canUpdate(string $feature): bool
    {
        return $this->hasPrivilege($feature . '_update');
    }

    public function canDelete(string $feature): bool
    {
        return $this->hasPrivilege($feature . '_delete');
    }

    /**
     * Check if has any CRUD access to a feature
     */
    public function hasAnyAccessTo(string $feature): bool
    {
        return $this->canCreate($feature) || 
               $this->canRead($feature) || 
               $this->canUpdate($feature) || 
               $this->canDelete($feature);
    }

    /**
     * Get role color for UI
     */
    public function getRoleColor(): string
    {
        return match($this->role) {
            'Admin' => 'red',
            'VP' => 'purple',
            'Manager Construction' => 'blue',
            'Project Manager' => 'green',
            'Project Control' => 'yellow',
            'Cost Control' => 'orange',
            'User' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Override password verification to handle plain text passwords
     */
    public function validateCredentials($password)
    {
        return $this->password === $password;
    }

    /**
     * Override getAuthPassword to return plain text password for comparison
     */
    public function getAuthPassword()
    {
        return $this->password;
    }
}
