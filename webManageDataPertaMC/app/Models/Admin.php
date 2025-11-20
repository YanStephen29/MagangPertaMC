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
        
        // Document Request Management
        'document_request_create' => 'Document Request - Create New',
        'document_request_assign' => 'Document Request - Assign Request',
        'document_request_status_update' => 'Document Request - Update Status',
        
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

        'hold_request_view' => 'Hold Request - View List',
        'hold_request_update' => 'Hold Request - Approve/Reject',
        
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
        'document_request' => [
            'label' => 'Document Request Management',
            'privileges' => [
                'document_request_create',
                'document_request_assign',
                'document_request_status_update'
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
        ],
        'hold_request' => [
            'label' => 'Hold Request Management',
            'privileges' => [
                'hold_request_view',
                'hold_request_update',
        ]           
        ]
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'admin_id', 'admin_id');
    }

    public function hasPrivilege(string $privilege): bool
    {
        if ($this->role === 'Admin') {
            return true;
        }
        
        if (in_array($privilege, $this->privilege ?? [])) {
            return true;
        }
        
        $mappedPrivilege = self::PRIVILEGE_MAP[$privilege] ?? null;
        if ($mappedPrivilege && in_array($mappedPrivilege, $this->privilege ?? [])) {
            return true;
        }
        
        return false;
    }


    public function hasAnyPrivilege(array $privileges): bool
    {
        if ($this->role === 'Admin') {
            return true;
        }
        
        return !empty(array_intersect($privileges, $this->privilege ?? []));
    }

    public function getAllPrivileges(): array
    {
        return $this->privilege ?? [];
    }

    private const PRIVILEGE_MAP = [
        'project_create' => 'project_add',
        'project_read' => 'project_view', 
        'project_update' => 'project_edit',
        'project_delete' => 'project_delete',
        
        'tools_create' => 'tools_add',
        'tools_read' => 'tools_view',
        'tools_update' => 'tools_edit', 
        'tools_delete' => 'tools_delete',
        
        'document_create' => 'document_add',
        'document_read' => 'document_view',
        'document_update' => 'document_edit',
        'document_delete' => 'document_delete',
        
        'boq_create' => 'boq_add',
        'boq_read' => 'boq_view',
        'boq_update' => 'boq_edit',
        'boq_delete' => 'boq_delete',
        
        'account_create' => 'account_add',
        'account_read' => 'account_view',
        'account_update' => 'account_edit',
        'account_delete' => 'account_delete',
        
        'bidang_create' => 'kode_bidang_add',
        'bidang_read' => 'kode_bidang_view',
        'bidang_update' => 'kode_bidang_edit',
        'bidang_delete' => 'kode_bidang_delete',

        'hold_request_read' => 'hold_request_view',
        'hold_request_update' => 'hold_request_update',
    ];


    public function canCreate(string $feature): bool
    {
        $privilege = $feature . '_create';
        $mappedPrivilege = self::PRIVILEGE_MAP[$privilege] ?? $privilege;
        return $this->hasPrivilege($mappedPrivilege);
    }

    public function canRead(string $feature): bool
    {
        $privilege = $feature . '_read';
        $mappedPrivilege = self::PRIVILEGE_MAP[$privilege] ?? $privilege;
        return $this->hasPrivilege($mappedPrivilege);
    }

    public function canUpdate(string $feature): bool
    {
        $privilege = $feature . '_update';
        $mappedPrivilege = self::PRIVILEGE_MAP[$privilege] ?? $privilege;
        return $this->hasPrivilege($mappedPrivilege);
    }

    public function canDelete(string $feature): bool
    {
        $privilege = $feature . '_delete';
        $mappedPrivilege = self::PRIVILEGE_MAP[$privilege] ?? $privilege;
        return $this->hasPrivilege($mappedPrivilege);
    }
    
    public function revokeBoqPrivileges(): bool
    {
        // Only apply to Project Manager role
        if ($this->role !== 'Project Manager') {
            return false;
        }
        
        // Define BOQ-related privileges to remove
        $boqPrivileges = [
            'boq_add', 'boq_edit', 'boq_delete',
            'boq_section_add', 'boq_section_edit', 'boq_section_delete',
            'boq_detail_add', 'boq_detail_edit', 'boq_detail_delete'
        ];
        
        // Get current privileges
        $currentPrivileges = $this->privilege ?? [];
        
        // Remove BOQ management privileges
        $newPrivileges = array_diff($currentPrivileges, $boqPrivileges);
        
        // Update privileges
        $this->update(['privilege' => array_values($newPrivileges)]);
        
        return true;
    }

    public function restoreBoqPrivileges(): bool
    {
        // Hanya berlaku untuk Project Manager
        if ($this->role !== 'Project Manager') {
            return false;
        }
        
        // Tentukan daftar hak akses yang SAMA PERSIS seperti di revokeBoqPrivileges
        $boqPrivileges = [
            'boq_add', 'boq_edit', 'boq_delete',
            'boq_section_add', 'boq_section_edit', 'boq_section_delete',
            'boq_detail_add', 'boq_detail_edit', 'boq_detail_delete'
        ];
        
        // Ambil hak akses saat ini
        $currentPrivileges = $this->privilege ?? [];
        $newPrivileges = array_unique(array_merge($currentPrivileges, $boqPrivileges));
        
        // Update privilege
        $this->update(['privilege' => array_values($newPrivileges)]);
        
        return true;
    }
    
    /**
     * Check if PM has locked any BOQ (for determining their BOQ management access)
     */
    public function hasLockedAnyBoq(): bool
    {
        if ($this->role !== 'Project Manager') {
            return false;
        }
        
        // Check if any project assigned to this PM has a locked BOQ
        $hasLockedBoq = Project::where('assigned_to', $this->admin_id)
            ->whereHas('boq', function($query) {
                $query->where('status', 'Locked');
            })
            ->exists();
            
        return $hasLockedBoq;
    }
    
    /**
     * Check if PM can manage BOQ for a specific project
     */
    public function canManageBoq($project = null): bool
    {
        if ($this->role !== 'Project Manager') {
            return true; // Admin and other roles can always manage BOQ
        }
        
        // If no specific project, check globally (for backward compatibility)
        if (!$project) {
            return !$this->hasLockedAnyBoq();
        }
        
        // For specific project, check if this project's BOQ is locked
        $boq = $project->boq;
        if (!$boq) {
            return true; // No BOQ yet, can manage
        }
        
        return $boq->status !== 'Locked';
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
