<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showLoginForm()
    {
        return view('auth.admin-login-simple');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $admin = Admin::where('username', $credentials['username'])->first();
        
        if ($admin && $admin->password === $credentials['password']) {
            Auth::guard('admin')->login($admin);
            return redirect()->route('projects.index')->with('success', 'Login berhasil!');
        }
        
        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('splash');
    }

    /**
     * Handle GET logout requests (fallback)
     */
    public function logoutGet(Request $request)
    {
        // If someone tries to access logout via GET, perform the logout anyway
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('splash')->with('info', 'Anda telah berhasil logout.');
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        $projects = $admin->projects()->with(['tools'])->get();
        
        return view('admin.dashboard', compact('admin', 'projects'));
    }
}
