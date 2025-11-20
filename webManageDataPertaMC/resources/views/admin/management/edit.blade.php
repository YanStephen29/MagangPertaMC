<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    Edit User
                </h2>
                <p class="text-sm text-gray-600 mt-1">Edit Information and privilege user: {{ $admin->username }}</p>
            </div>
            <div class="mt-3 sm:mt-0">
                <a href="{{ route('admin.management.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md shadow-md transition-colors duration-200 inline-flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to User List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.management.update', $admin) }}" method="POST" id="editAdminForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Info -->
                            <div class="space-y-6">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h3 class="text-lg font-semibold text-blue-800 mb-4">📋 Basic Information</h3>
                                    
                                    <div class="flex items-center mb-4">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <div class="h-12 w-12 rounded-full flex items-center justify-center text-white font-bold text-sm" style="background-color: {{ $admin->getRoleColor() }}">
                                                {{ substr($admin->username, 0, 2) }}
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm text-gray-500">User ID: {{ $admin->admin_id }}</div>
                                            <div class="text-xs text-gray-400">Created At: {{ $admin->created_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                            Username <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="username" id="username" value="{{ old('username', $admin->username) }}" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('username') border-red-500 @enderror"
                                               placeholder="Masukkan username (max 20 karakter)" maxlength="20" required>
                                        @error('username')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mt-4">
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                            Password <span class="text-gray-500">(Leave blank if you don't want to change)</span>
                                        </label>
                                        <input type="password" name="password" id="password" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                                               placeholder="6-8 karakter" minlength="6" maxlength="8">
                                        @error('password')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-xs text-gray-500 mt-1">Password must be 6-8 characters or leave blank to keep unchanged</p>
                                    </div>

                                    <div class="mt-4">
                                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                            Role <span class="text-red-500">*</span>
                                        </label>
                                        <select name="role" id="role" 
                                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror"
                                                onchange="loadRoleTemplate()" required>
                                            @foreach(['Admin', 'VP', 'Manager Construction', 'Project Manager', 'Project Control', 'Cost Control', 'User'] as $role)
                                                <option value="{{ $role }}" {{ old('role', $admin->role) == $role ? 'selected' : '' }}>{{ $role }}</option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Privileges -->
                            <div class="space-y-6">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <h3 class="text-lg font-semibold text-green-800 mb-4">🔐 Privileges</h3>
                                    
                                    <div class="mb-4">
                                        <button type="button" onclick="loadRoleTemplate()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                            Load Template for Role
                                        </button>
                                        <button type="button" onclick="selectAll()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 ml-2">
                                            Select All
                                        </button>
                                        <button type="button" onclick="clearAll()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 ml-2">
                                            Clear All
                                        </button>
                                    </div>

                                    <div class="max-h-80 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-white">
                                        @php
                                            $privilegeGroups = \App\Models\Admin::PRIVILEGE_GROUPS;
                                        @endphp
                                        
                                        @foreach($privilegeGroups as $groupKey => $groupData)
                                            <div class="mb-3 border-b border-gray-200 pb-2 last:border-b-0">
                                                <div class="flex items-center mb-2">
                                                    <input type="checkbox" 
                                                           id="group-{{ $groupKey }}" 
                                                           class="group-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 mr-2"
                                                           onchange="toggleGroup('{{ $groupKey }}')">
                                                    <label for="group-{{ $groupKey }}" class="text-sm font-semibold text-gray-800 cursor-pointer">
                                                        {{ $groupData['label'] }}
                                                    </label>
                                                </div>
                                                
                                                <div class="ml-6 space-y-1">
                                                    @foreach($groupData['privileges'] as $privilege)
                                                        @if(array_key_exists($privilege, $availablePrivileges))
                                                            <label class="flex items-center py-1 px-2 hover:bg-gray-50 rounded cursor-pointer">
                                                                <input type="checkbox" 
                                                                       name="privilege[]" 
                                                                       value="{{ $privilege }}" 
                                                                       class="privilege-checkbox group-{{ $groupKey }} rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 mr-2"
                                                                       onchange="updateGroupToggle('{{ $groupKey }}')"
                                                                       {{ in_array($privilege, old('privilege', $admin->privilege ?? [])) ? 'checked' : '' }}>
                                                                <div class="flex-1">
                                                                    <div class="text-sm text-gray-700">{{ $availablePrivileges[$privilege] }}</div>
                                                                </div>
                                                            </label>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        <!-- Legacy/Other Privileges -->
                                        @php
                                            $legacyPrivileges = ['full_access', 'manage_admin', 'view_reports'];
                                        @endphp
                                        @if(!empty($legacyPrivileges))
                                            <div class="border border-gray-300 rounded-lg p-3">
                                                <h4 class="font-semibold text-gray-800 mb-3">System Access</h4>
                                                <div class="grid grid-cols-1 gap-2 ml-6">
                                                    @foreach($legacyPrivileges as $key)
                                                        @if(isset(\App\Models\Admin::PRIVILEGES[$key]))
                                                            <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                                                                <input type="checkbox" name="privilege[]" value="{{ $key }}" 
                                                                       class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mr-3"
                                                                       {{ in_array($key, old('privilege', $admin->privilege ?? [])) ? 'checked' : '' }}>
                                                                <div class="flex-1">
                                                                    <div class="text-sm font-medium text-gray-900">{{ \App\Models\Admin::PRIVILEGES[$key] }}</div>
                                                                    <div class="text-xs text-gray-500">{{ $key }}</div>
                                                                </div>
                                                            </label>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @error('privilege')
                                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                    @enderror

                                    <p class="text-xs text-gray-500 mt-2">Choose at least 1 privilege</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end mt-8">
                            <div class="flex gap-4">
                                <a href="{{ route('admin.management.show', $admin) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md font-medium transition-colors duration-200">
                                    Cancel
                                </a>
                                <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-2 rounded-md font-medium shadow-md transform hover:scale-105 transition-all duration-200">
                                    Update Admin
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Role templates with new privilege structure
        const roleTemplates = {
            'Admin': ['full_access'],
            'VP': ['project_view', 'boq_view', 'document_view', 'view_reports'],
            'Manager Construction': ['project_view', 'project_add', 'project_edit', 'tools_view', 'tools_create', 'tools_edit', 'document_view', 'document_create', 'document_edit', 'boq_view'],
            'Project Manager': ['project_view', 'project_add', 'project_edit', 'project_assign', 'tools_view', 'tools_create', 'tools_edit', 'tools_assign', 'tools_update_status', 'document_view', 'document_create', 'document_edit', 'boq_create', 'boq_view', 'boq_update', 'boq_section_add', 'boq_section_edit', 'boq_section_view', 'boq_detail_create', 'boq_detail_update', 'boq_detail_view'],
            'Project Control': ['project_view', 'tools_view', 'tools_update_status', 'document_view', 'document_edit_tahapan', 'boq_view'],
            'Cost Control': ['project_view', 'tools_view', 'document_view', 'boq_view', 'boq_detail_view'],
            'User': ['project_view', 'tools_view', 'document_view', 'boq_view']
        };

        function loadRoleTemplate() {
            const roleSelect = document.getElementById('role');
            const selectedRole = roleSelect.value;
            
            if (selectedRole && roleTemplates[selectedRole]) {
                // Clear all checkboxes first
                clearAll();
                
                // Check the privileges for this role
                const privileges = roleTemplates[selectedRole];
                privileges.forEach(privilege => {
                    const checkbox = document.querySelector(`input[name="privilege[]"][value="${privilege}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
                
                // Update group toggles
                updateAllGroupToggles();
            }
        }

        function selectAll() {
            const checkboxes = document.querySelectorAll('input[name="privilege[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = true);
            
            // Update group toggles
            const groupToggles = document.querySelectorAll('.group-toggle');
            groupToggles.forEach(toggle => toggle.checked = true);
        }

        function clearAll() {
            const checkboxes = document.querySelectorAll('input[name="privilege[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = false);
            
            // Update group toggles
            const groupToggles = document.querySelectorAll('.group-toggle');
            groupToggles.forEach(toggle => toggle.checked = false);
        }

        function toggleGroup(groupName) {
            const groupToggle = document.querySelector(`.group-toggle[data-group="${groupName}"]`);
            const groupCheckboxes = document.querySelectorAll(`.group-${groupName}`);
            
            groupCheckboxes.forEach(checkbox => {
                checkbox.checked = groupToggle.checked;
            });
        }

        function updateGroupToggle(groupName) {
            const groupCheckboxes = document.querySelectorAll(`.group-${groupName}`);
            const groupToggle = document.querySelector(`.group-toggle[data-group="${groupName}"]`);
            
            const checkedCount = Array.from(groupCheckboxes).filter(cb => cb.checked).length;
            const totalCount = groupCheckboxes.length;
            
            if (checkedCount === 0) {
                groupToggle.checked = false;
                groupToggle.indeterminate = false;
            } else if (checkedCount === totalCount) {
                groupToggle.checked = true;
                groupToggle.indeterminate = false;
            } else {
                groupToggle.checked = false;
                groupToggle.indeterminate = true;
            }
        }

        function updateAllGroupToggles() {
            const groups = @json(array_keys(\App\Models\Admin::PRIVILEGE_GROUPS));
            groups.forEach(groupName => {
                updateGroupToggle(groupName);
            });
        }

        // Initialize group toggles on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateAllGroupToggles();
        });
    </script>
</x-app-layout>