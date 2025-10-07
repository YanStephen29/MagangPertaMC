<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    Detail User
                </h2>
                <p class="text-sm text-gray-600 mt-1">Informasi Lengkap User : {{ $admin->username }}</p>
            </div>
            <div class="flex gap-2 mt-3 sm:mt-0">
                @if(auth('admin')->user()->hasPrivilege('manage_admin') || auth('admin')->user()->hasPrivilege('full_access'))
                    <a href="{{ route('admin.management.edit', $admin) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-md transition-colors duration-200 inline-flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Admin
                    </a>
                @endif
                <a href="{{ route('admin.management.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md shadow-md transition-colors duration-200 inline-flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-800">📋 Informasi Dasar</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-16 w-16">
                                <div class="h-16 w-16 rounded-full flex items-center justify-center text-white font-bold text-xl" style="background-color: {{ $admin->getRoleColor() }}">
                                    {{ substr($admin->username, 0, 2) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-xl font-bold text-gray-900">{{ $admin->username }}</div>
                                <div class="text-sm text-gray-500">User ID : {{ $admin->admin_id }}</div>
                                <div class="mt-2">
                                    <div class="text-sm text-gray-500">Role :
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white" style="background-color: {{ $admin->getRoleColor() }}">
                                        {{ $admin->role }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat :</dt>
                                    <dd class="text-sm text-black-1000">{{ $admin->created_at->format('d F Y, H:i') }} WIB</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Terakhir Update</dt>
                                    <dd class="text-sm text-black-1000">{{ $admin->updated_at->format('d F Y, H:i') }} WIB</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Privileges</dt>
                                    <dd class="text-sm text-black-1000 font-semibold">{{ count($admin->privilege) }} privileges</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Privileges -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-green-100 border-b border-green-200">
                        <h3 class="text-lg font-semibold text-green-800">🔐 Privileges & Permissions</h3>
                    </div>
                    <div class="p-6">
                        @php
                            $privilegeLabels = [
                                'view_projects' => 'View Projects',
                                'create_projects' => 'Create Projects', 
                                'edit_projects' => 'Edit Projects',
                                'delete_projects' => 'Delete Projects',
                                'manage_tools' => 'Manage Tools',
                                'manage_documents' => 'Manage Documents',
                                'manage_requests' => 'Manage Requests',
                                'manage_bidang' => 'Manage Bidang',
                                'view_reports' => 'View Reports',
                                'manage_admin' => 'Manage Admin Users',
                                'view_dashboard' => 'View Dashboard',
                                'export_data' => 'Export Data',
                                'import_data' => 'Import Data',
                                'system_settings' => 'System Settings',
                                'audit_logs' => 'View Audit Logs',
                                'full_access' => 'Full System Access'
                            ];
                        @endphp
                        
                        <div class="grid grid-cols-1 gap-2">
                            @foreach($admin->privilege as $privilege)
                                <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if($privilege === 'full_access')
                                                <div class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center">
                                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9.5 9.293 10.793a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @elseif(str_contains($privilege, 'manage'))
                                                <div class="h-8 w-8 rounded-full bg-orange-500 flex items-center justify-center">
                                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $privilegeLabels[$privilege] ?? ucfirst(str_replace('_', ' ', $privilege)) }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $privilege }}</div>
                                        </div>
                                    </div>
                                    
                                    @if($privilege === 'full_access')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Super Admin
                                        </span>
                                    @elseif(str_contains($privilege, 'manage'))
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            Management
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Access
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects by Admin (if any) -->
            @if($admin->projects->count() > 0)
                <div class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-purple-100 border-b border-purple-200">
                        <h3 class="text-lg font-semibold text-purple-800">📊 Projects Terkait</h3>
                        <p class="text-sm text-purple-600">Projects yang dibuat atau dikelola oleh admin ini</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($admin->projects->take(6) as $project)
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                    <div class="font-medium text-purple-900">{{ $project->title_project }}</div>
                                    <div class="text-sm text-purple-600">{{ $project->no_IO }}</div>
                                    <div class="text-xs text-gray-500 mt-2">
                                        Created: {{ $project->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($admin->projects->count() > 6)
                            <div class="mt-4 text-center">
                                <span class="text-sm text-gray-500">
                                    Dan {{ $admin->projects->count() - 6 }} projects lainnya...
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>