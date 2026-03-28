<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Blade directive untuk privilege check
        Blade::if('canAccess', function ($privilege) {
            $user = auth('admin')->user();
            if (!$user) return false;
            
            // Admin role otomatis dapat akses semua
            if ($user->role === 'Admin') return true;
            
            // Privilege mapping for consistency
            $privilegeMap = [
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
            ];
            
            // Map privilege to actual database value
            $mappedPrivilege = $privilegeMap[$privilege] ?? $privilege;
            
            // Check privilege di database
            $privileges = $user->privilege ?? [];
            return in_array($mappedPrivilege, $privileges);
        });
        
        // Blade directive untuk disabled button dengan privilege (deprecated - gunakan @canAccess untuk conditional rendering)
        Blade::directive('privilegeButton', function ($expression) {
            $parts = explode(',', $expression);
            $privilege = trim($parts[0], " '\"");
            $actionName = isset($parts[1]) ? trim($parts[1], " '\"") : 'melakukan aksi ini';
            
            return "<?php 
                \$user = auth('admin')->user();
                
                // Privilege mapping for consistency
                \$privilegeMap = [
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
                ];
                
                // Map privilege to actual database value
                \$mappedPrivilege = \$privilegeMap['$privilege'] ?? '$privilege';
                
                \$hasPrivilege = \$user && (\$user->role === 'Admin' || in_array(\$mappedPrivilege, \$user->privilege ?? []));
                echo \$hasPrivilege ? '' : 'btn-disabled-privilege disabled';
                echo \$hasPrivilege ? '' : ' data-action=\"$actionName\"';
            ?>";
        });
        
        // Blade directive untuk empty data state
        Blade::directive('emptyDataState', function ($expression) {
            $parts = explode(',', $expression);
            $title = isset($parts[0]) ? trim($parts[0], " '\"") : 'Data Tidak Tersedia';
            $message = isset($parts[1]) ? trim($parts[1], " '\"") : 'Anda tidak memiliki akses untuk melihat data ini.';
            
            return "<?php echo '
                <div class=\"empty-data-container\">
                    <div class=\"empty-data-icon\">
                        <i class=\"fas fa-lock\"></i>
                    </div>
                    <h4 class=\"empty-data-title\">$title</h4>
                    <p class=\"empty-data-message\">$message</p>
                </div>
            '; ?>";
        });
    }
}
