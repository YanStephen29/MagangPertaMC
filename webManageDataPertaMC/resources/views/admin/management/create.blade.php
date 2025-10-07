<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    Register New User
                </h2>
                <p class="text-sm text-gray-600 mt-1">Create a new user account with the appropriate role privileges!</p>
            </div>
            <div class="mt-3 sm:mt-0">
                <a href="{{ route('admin.management.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md shadow-md transition-colors duration-200 inline-flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back To User List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.management.store') }}" method="POST" id="adminForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Info -->
                            <div class="space-y-6">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h3 class="text-lg font-semibold text-blue-800 mb-4"> User Information</h3>
                                    
                                    <div>
                                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                            Username <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="username" id="username" value="{{ old('username') }}" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('username') border-red-500 @enderror"
                                               placeholder="Enter username (Max 20 characters)" maxlength="20" required>
                                        @error('username')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mt-4">
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                            Password <span class="text-red-500">*</span>
                                        </label>
                                        <input type="password" name="password" id="password" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                                               placeholder="6-8 characters" minlength="6" maxlength="8" required>
                                        @error('password')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-xs text-gray-500 mt-1">Password must be 6-8 characters</p>
                                    </div>

                                    <div class="mt-4">
                                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                            Role <span class="text-red-500">*</span>
                                        </label>
                                        <select name="role" id="role" 
                                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror"
                                                onchange="loadRoleTemplate()" required>
                                            <option value="">Choose Role...</option>
                                            @foreach(['Admin', 'VP', 'Manager Construction', 'Project Manager', 'Project Control', 'Cost Control', 'User'] as $role)
                                                <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
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
                                            Load Template for your Role
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
                                                           class="group-toggle rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 mr-2"
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
                                                                       class="privilege-checkbox group-{{ $groupKey }} rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 mr-2"
                                                                       onchange="updateGroupToggle('{{ $groupKey }}')"
                                                                       {{ in_array($privilege, old('privilege', [])) ? 'checked' : '' }}>
                                                                <div class="flex-1">
                                                                    <div class="text-sm text-gray-700">{{ $availablePrivileges[$privilege] }}</div>
                                                                </div>
                                                            </label>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
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
                                <a href="{{ route('admin.management.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md font-medium transition-colors duration-200">
                                    Cancel
                                </a>
                                <button type="submit" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-2 rounded-md font-medium shadow-md transform hover:scale-105 transition-all duration-200">
                                    Register User
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Role templates from controller
        const roleTemplates = @json($roleTemplates);

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
                
                // Update all group toggles
                updateAllGroupToggles();
            }
        }

        function selectAll() {
            const checkboxes = document.querySelectorAll('input[name="privilege[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = true);
            
            // Update all group toggles
            updateAllGroupToggles();
        }

        function clearAll() {
            const checkboxes = document.querySelectorAll('input[name="privilege[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = false);
            
            const groupToggles = document.querySelectorAll('.group-toggle');
            groupToggles.forEach(toggle => {
                toggle.checked = false;
                toggle.indeterminate = false;
            });
        }

        // Group toggle functions
        function toggleGroup(groupKey) {
            const groupToggle = document.getElementById(`group-${groupKey}`);
            const groupCheckboxes = document.querySelectorAll(`.group-${groupKey}`);
            
            groupCheckboxes.forEach(checkbox => {
                checkbox.checked = groupToggle.checked;
            });
            
            // Clear indeterminate state
            groupToggle.indeterminate = false;
        }

        function updateGroupToggle(groupKey) {
            const groupCheckboxes = document.querySelectorAll(`.group-${groupKey}`);
            const groupToggle = document.getElementById(`group-${groupKey}`);
            
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
            const privilegeGroups = @json(array_keys(\App\Models\Admin::PRIVILEGE_GROUPS));
            privilegeGroups.forEach(groupKey => {
                updateGroupToggle(groupKey);
            });
        }

        // Initialize group toggles on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateAllGroupToggles();
        });
    </script>
</x-app-layout>