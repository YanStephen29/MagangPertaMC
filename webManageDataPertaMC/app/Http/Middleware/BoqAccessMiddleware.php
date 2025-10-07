<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BoqAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Please login to access BOQ management.');
        }
        
        $admin = Auth::guard('admin')->user();
        
        // Check if user role is Admin or Project Manager
        $allowedRoles = ['Admin', 'Project Manager'];
        
        if (!in_array($admin->role, $allowedRoles)) {
            return redirect()->back()->with('error', 'Access denied. Only Admin and Project Manager can manage BOQ.');
        }
        
        return $next($request);
    }
}
