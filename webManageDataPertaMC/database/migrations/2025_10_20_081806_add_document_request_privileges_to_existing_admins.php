<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all admins
        $admins = \App\Models\Admin::all();
        
        foreach ($admins as $admin) {
            $currentPrivileges = $admin->privilege ?? [];
            $newPrivileges = [
                'document_request_create',
                'document_request_assign', 
                'document_request_status_update'
            ];
            
            // Add new privileges based on role
            switch ($admin->role) {
                case 'Admin':
                    // Admin gets all privileges
                    $updatedPrivileges = array_unique(array_merge($currentPrivileges, $newPrivileges));
                    break;
                    
                case 'VP':
                case 'Manager Construction': 
                case 'Project Manager':
                case 'Project Control':
                    // These roles get all document request privileges
                    $updatedPrivileges = array_unique(array_merge($currentPrivileges, $newPrivileges));
                    break;
                    
                case 'Cost Control':
                case 'User':
                    // These roles don't get document request privileges by default
                    $updatedPrivileges = $currentPrivileges;
                    break;
                    
                default:
                    $updatedPrivileges = $currentPrivileges;
                    break;
            }
            
            // Update admin privileges
            $admin->update([
                'privilege' => array_values($updatedPrivileges)
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove document request privileges from all admins
        $admins = \App\Models\Admin::all();
        
        $privilegesToRemove = [
            'document_request_create',
            'document_request_assign',
            'document_request_status_update'
        ];
        
        foreach ($admins as $admin) {
            $currentPrivileges = $admin->privilege ?? [];
            $updatedPrivileges = array_diff($currentPrivileges, $privilegesToRemove);
            
            $admin->update([
                'privilege' => array_values($updatedPrivileges)
            ]);
        }
    }
};
