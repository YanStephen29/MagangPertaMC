<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $privileges = null): Response
    {
        // Check if admin is authenticated
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $admin = Auth::guard('admin')->user();

        // If specific privileges are required, check them
        if ($privileges) {
            // Split privileges by comma to support multiple privileges (OR logic)
            $requiredPrivileges = explode(',', $privileges);
            $hasRequiredPrivilege = false;
            
            foreach ($requiredPrivileges as $privilege) {
                $privilege = trim($privilege);
                
                // Admin role automatically has all privileges
                if ($admin->role === 'Admin') {
                    $hasRequiredPrivilege = true;
                    break;
                }
                
                // Check using new CRUD privilege methods
                if ($this->hasPrivilegeByAction($admin, $privilege, $request)) {
                    $hasRequiredPrivilege = true;
                    break;
                }
            }
            
            if (!$hasRequiredPrivilege) {
                abort(403, 'Unauthorized. You do not have any of the required privileges: ' . $privileges);
            }
        }

        return $next($request);
    }
    
    /**
     * Check if admin has privilege based on action and request method
     */
    private function hasPrivilegeByAction($admin, $privilege, $request): bool
    {
        // If privilege is already a CRUD privilege (contains underscore), check directly
        if (strpos($privilege, '_') !== false) {
            return $admin->hasPrivilege($privilege);
        }
        
        // Otherwise, determine CRUD action based on route name and HTTP method
        $action = $this->determineCrudAction($request);
        $crudPrivilege = $privilege . '_' . $action;
        
        return $admin->hasPrivilege($crudPrivilege);
    }
    
    /**
     * Determine CRUD action from request
     */
    private function determineCrudAction($request): string
    {
        $routeName = $request->route()->getName();
        $method = $request->method();
        
        // Check route name patterns first
        if (strpos($routeName, '.create') !== false) {
            return 'create';
        }
        
        if (strpos($routeName, '.edit') !== false || strpos($routeName, '.update') !== false) {
            return 'update';
        }
        
        if (strpos($routeName, '.destroy') !== false) {
            return 'delete';
        }
        
        if (strpos($routeName, '.index') !== false || strpos($routeName, '.show') !== false) {
            return 'read';
        }
        
        // Fallback to HTTP method
        switch ($method) {
            case 'GET':
                return 'read';
            case 'POST':
                return 'create';
            case 'PUT':
            case 'PATCH':
                return 'update';
            case 'DELETE':
                return 'delete';
            default:
                return 'read';
        }
    }
}
