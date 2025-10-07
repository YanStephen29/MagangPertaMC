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
            
            // Check privilege di database
            $privileges = $user->privilege ?? [];
            return in_array($privilege, $privileges);
        });
        
        // Blade directive untuk disabled button dengan privilege
        Blade::directive('privilegeButton', function ($expression) {
            $parts = explode(',', $expression);
            $privilege = trim($parts[0], " '\"");
            $actionName = isset($parts[1]) ? trim($parts[1], " '\"") : 'melakukan aksi ini';
            
            return "<?php 
                \$user = auth('admin')->user();
                \$hasPrivilege = \$user && (\$user->role === 'Admin' || in_array('$privilege', \$user->privilege ?? []));
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
