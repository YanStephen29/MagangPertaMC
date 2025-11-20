<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.management.index', compact('admins'));
    }

    public function create()
    {
        $privilegeGroups = Admin::PRIVILEGE_GROUPS;
        
        $availablePrivileges = [];
        foreach ($privilegeGroups as $groupData) {
            foreach ($groupData['privileges'] as $privilege) {
                // Create readable labels from keys
                $label = ucwords(str_replace('_', ' ', $privilege));
                $availablePrivileges[$privilege] = $label;
            }
        }

        // Define role templates with new privilege structure
        $roleTemplates = [
            'Admin' => [
                'project_view', 'project_add', 'project_edit', 'project_delete', 'project_assign',
                'tools_view', 'tools_add', 'tools_edit', 'tools_delete',
                'kode_bidang_view', 'kode_bidang_add', 'kode_bidang_edit', 'kode_bidang_delete', 'kode_bidang_view',
                'document_view', 'document_add', 'document_edit', 'document_delete',
                'document_request_create', 'document_request_assign', 'document_request_status_update',
                'boq_view', 'boq_add', 'boq_edit', 'boq_delete', 'boq_assign',
                'boq_section_view', 'boq_section_add', 'boq_section_edit', 'boq_section_delete',
                'boq_detail_view', 'boq_detail_add', 'boq_detail_edit', 'boq_detail_delete',
                'account_view', 'account_add', 'account_edit', 'account_delete','hold_request_view', 'hold_request_update'
            ],
            'VP' => [
                'project_view', 'tools_view', 'kode_bidang_view', 'document_view', 'document_request_create', 'document_request_assign', 'document_request_status_update', 'boq_view', 'boq_section_view', 'boq_detail_view'
            ],
            'Manager Construction' => [
                'project_view', 'project_add', 'project_edit', 'project_assign',
                'tools_view', 'tools_add', 'tools_edit',
                'document_view', 'document_add', 'document_edit',
                'document_request_create', 'document_request_assign', 'document_request_status_update',
                'boq_view', 'boq_add', 'boq_edit', 'boq_assign',
                'boq_section_view', 'boq_section_add', 'boq_section_edit',
                'boq_detail_view', 'boq_detail_add', 'boq_detail_edit'
            ],
            'Project Manager' => [
                'project_view', 'project_edit', 'project_assign',
                'tools_view', 'tools_add', 'tools_edit', 'tools_delete',
                'document_view', 'document_edit',
                'document_request_create', 'document_request_assign', 'document_request_status_update',
                'boq_view', 'boq_edit', 'boq_assign',
                'boq_section_view', 'boq_section_add', 'boq_section_edit', 'boq_section_delete',
                'boq_detail_view', 'boq_detail_add', 'boq_detail_edit', 'boq_detail_delete','hold_request_view'
            ],
            'Project Control' => [
                'project_view',
                'tools_view', 'tools_edit',
                'document_view', 'document_edit',
                'document_request_create', 'document_request_assign', 'document_request_status_update',
                'boq_view', 'boq_section_view', 'boq_section_edit',
                'boq_detail_view', 'boq_detail_add', 'boq_detail_edit'
            ],
            'Cost Control' => [
                'project_view', 'tools_view', 'document_view', 
                'boq_view', 'boq_section_view', 'boq_detail_view'
            ],
            'User' => [
                'project_view', 'tools_view', 'document_view'
            ]
        ];

        return view('admin.management.create', compact('availablePrivileges', 'roleTemplates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:20|unique:admins,username',
            'password' => 'required|string|min:6|max:8',
            'role' => 'required|string|in:Admin,VP,Manager Construction,Project Manager,Project Control,Cost Control,User',
            'privilege' => 'required|array|min:1',
            'privilege.*' => 'string'
        ]);

        $admin = Admin::create([
            'username' => $request->username,
            'password' => $request->password,
            'role' => $request->role,
            'privilege' => $request->privilege
        ]);

        return redirect()->route('admin.management.index')
                        ->with('success', "Admin {$admin->username} berhasil didaftarkan dengan role {$admin->role}");
    }

    public function show(Admin $admin)
    {
        return view('admin.management.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        $privilegeGroups = Admin::PRIVILEGE_GROUPS;
        
        $availablePrivileges = [];
        foreach ($privilegeGroups as $groupData) {
            foreach ($groupData['privileges'] as $privilege) {
                $label = ucwords(str_replace('_', ' ', $privilege));
                $availablePrivileges[$privilege] = $label;
            }
        }

        $roleTemplates = [
            'Admin' => [
                'project_view', 'project_add', 'project_edit', 'project_delete', 'project_assign',
                'tools_view', 'tools_add', 'tools_edit', 'tools_delete',
                'kode_bidang_view', 'kode_bidang_add', 'kode_bidang_edit', 'kode_bidang_delete', 'kode_bidang_view',
                'document_view', 'document_add', 'document_edit', 'document_delete',
                'document_request_create', 'document_request_assign', 'document_request_status_update',
                'boq_view', 'boq_add', 'boq_edit', 'boq_delete', 'boq_assign',
                'boq_section_view', 'boq_section_add', 'boq_section_edit', 'boq_section_delete',
                'boq_detail_view', 'boq_detail_add', 'boq_detail_edit', 'boq_detail_delete',
                'account_view', 'account_add', 'account_edit', 'account_delete'
            ],
            'VP' => [
                'project_view', 'tools_view', 'document_view', 'document_request_create', 'document_request_assign', 'document_request_status_update', 'boq_view', 'boq_section_view', 'boq_detail_view'
            ],
            'Manager Construction' => [
                'project_view', 'project_add', 'project_edit', 'project_assign',
                'tools_view', 'tools_add', 'tools_edit',
                'document_view', 'document_add', 'document_edit',
                'document_request_create', 'document_request_assign', 'document_request_status_update',
                'boq_view', 'boq_add', 'boq_edit', 'boq_assign',
                'boq_section_view', 'boq_section_add', 'boq_section_edit',
                'boq_detail_view', 'boq_detail_add', 'boq_detail_edit'
            ],
            'Project Manager' => [
                'project_view', 'project_edit', 'project_assign',
                'tools_view', 'tools_add', 'tools_edit', 'tools_delete',
                'document_view', 'document_edit',
                'document_request_create', 'document_request_assign', 'document_request_status_update',
                'boq_view', 'boq_edit', 'boq_assign',
                'boq_section_view', 'boq_section_add', 'boq_section_edit', 'boq_section_delete',
                'boq_detail_view', 'boq_detail_add', 'boq_detail_edit', 'boq_detail_delete'
            ],
            'Project Control' => [
                'project_view',
                'tools_view', 'tools_edit',
                'document_view', 'document_edit',
                'document_request_create', 'document_request_assign', 'document_request_status_update',
                'boq_view', 'boq_section_view', 'boq_section_edit',
                'boq_detail_view', 'boq_detail_add', 'boq_detail_edit'
            ],
            'Cost Control' => [
                'project_view', 'tools_view', 'document_view', 
                'boq_view', 'boq_section_view', 'boq_detail_view'
            ],
            'User' => [
                'project_view', 'tools_view', 'document_view'
            ]
        ];

        return view('admin.management.edit', compact('admin', 'availablePrivileges', 'roleTemplates'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'username' => 'required|string|max:20|unique:admins,username,' . $admin->admin_id . ',admin_id',
            'password' => 'nullable|string|min:6|max:8',
            'role' => 'required|string|in:Admin,VP,Manager Construction,Project Manager,Project Control,Cost Control,User',
            'privilege' => 'required|array|min:1',
            'privilege.*' => 'string'
        ]);

        $updateData = [
            'username' => $request->username,
            'role' => $request->role,
            'privilege' => $request->privilege
        ];

        if ($request->filled('password')) {
            $updateData['password'] = $request->password;
        }

        $admin->update($updateData);

        return redirect()->route('admin.management.index')
                        ->with('success', "Admin {$admin->username} berhasil diupdate");
    }

    public function destroy(Admin $admin)
    {
        $currentAdmin = Auth::guard('admin')->user();
        
        if ($admin->admin_id === $currentAdmin->admin_id) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $username = $admin->username;
        $admin->delete();

        return redirect()->route('admin.management.index')
                        ->with('success', "Admin {$username} berhasil dihapus");
    }
}
