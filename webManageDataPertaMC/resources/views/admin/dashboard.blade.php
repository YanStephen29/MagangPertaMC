<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    � Admin Dashboard
                </h2>
                <p class="text-sm text-gray-600 mt-1">Welcome back, {{ $admin->username }}!</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <!-- Admin Info Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-red-50 to-blue-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">🏷️ Account Information</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-16 w-16">
                            <div class="h-16 w-16 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg" style="background-color: {{ $admin->getRoleColor() }}">
                                {{ substr($admin->username, 0, 2) }}
                            </div>
                        </div>
                        <div class="ml-6">
                            <div class="text-xl font-bold text-gray-900">{{ $admin->username }}</div>
                            <div class="text-sm text-gray-500">Admin ID: {{ $admin->admin_id }}</div>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white shadow-sm" style="background-color: {{ $admin->getRoleColor() }}">
                                    {{ $admin->role }}
                                </span>
                                <span class="ml-2 text-sm text-gray-600">{{ count($admin->privilege) }} privileges</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Quick Actions</h4>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('projects.index') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z"></path>
                                </svg>
                                View Projects
                            </a>
                            
                            @if($admin->hasPrivilege('manage_documents') || $admin->hasPrivilege('full_access'))
                                <a href="{{ route('documents.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Manage Documents
                                </a>
                            @endif
                            
                            @if($admin->hasPrivilege('manage_admin') || $admin->hasPrivilege('full_access'))
                                <a href="{{ route('admin.management.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    Manage Admins
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($projects->count() > 0)
                <!-- Projects Overview -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-purple-100 border-b border-purple-200">
                        <h3 class="text-lg font-semibold text-purple-800">📊 Your Projects</h3>
                        <p class="text-sm text-purple-600">Projects you've created or manage</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($projects->take(6) as $project)
                                <div class="border border-purple-200 rounded-lg p-4 hover:border-purple-300 transition-colors duration-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-medium text-gray-900 truncate">
                                                {{ $project->title_project }}
                                            </h4>
                                            <p class="text-xs text-gray-500 mt-1">{{ $project->no_IO }}</p>
                                            <p class="text-xs text-purple-600 mt-2">
                                                {{ $project->tools->count() }} tools
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0 ml-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Active
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('projects.tools.index', $project) }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                            View Tools →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($projects->count() > 6)
                            <div class="mt-4 text-center">
                                <a href="{{ route('projects.index') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                                    View All {{ $projects->count() }} Projects →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- No Projects -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                    <div class="p-12 text-center">
                        <div class="mb-4">
                            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z"></path>
                            </svg>
                        </div>
                        <p class="text-xl text-gray-500 mb-6">No projects found</p>
                        <p class="text-gray-400 mb-6">You haven't created any projects yet.</p>
                        @if($admin->hasPrivilege('create_projects') || $admin->hasPrivilege('full_access'))
                            <a href="{{ route('projects.index') }}" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium py-2 px-6 rounded-md shadow-md transform hover:scale-105 transition-all duration-200 inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create Your First Project
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- System Stats (if admin has privileges) -->
            @if($admin->hasPrivilege('view_reports') || $admin->hasPrivilege('full_access'))
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-red-100 rounded-md">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Projects</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Project::count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-md">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Documents</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Document::count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-md">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Admins</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Admin::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>