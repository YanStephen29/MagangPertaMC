<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    Admin Management
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage admin users and their privileges</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto mt-3 sm:mt-0">
                @canAccess('account_create')
                    <a href="{{ route('admin.management.create') }}" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-medium py-2 px-4 rounded-md shadow-md transform hover:scale-105 transition-all duration-200 inline-flex items-center text-sm justify-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Register New Admin
                    </a>
                @else
                    <button class="@privilegeButton('account_create', 'membuat admin baru') bg-gray-400 text-white font-medium py-2 px-4 rounded-md inline-flex items-center text-sm justify-center">
                        <i class="fas fa-lock w-4 h-4 mr-1"></i>
                        Register New Admin
                    </button>
                @endcanAccess
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-3 sm:p-6 text-gray-900">
                    <!-- Notifikasi sudah ditangani di layout utama (app.blade.php) -->

                    <div class="overflow-x-auto -mx-3 sm:mx-0">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-blue-50 to-purple-50">
                                <tr>
                                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider border-r border-gray-200">
                                        Admin Info
                                    </th>
                                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider border-r border-gray-200">
                                        Password
                                    </th>
                                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider border-r border-gray-200">
                                        Role & Privileges
                                    </th>
                                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider border-r border-gray-200">
                                        Created Date
                                    </th>
                                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($admins as $admin)
                                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 border-r border-gray-100">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-bold text-sm" style="background-color: {{ $admin->getRoleColor() }}">
                                                        {{ substr($admin->username, 0, 2) }}
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $admin->username }}</div>
                                                    <div class="text-sm text-gray-500">ID: {{ $admin->admin_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 border-r border-gray-100">
                                            <div class="flex items-center space-x-2">
                                                <div class="relative">
                                                    <input type="text" 
                                                           id="password-{{ $admin->admin_id }}" 
                                                           value="{{ $admin->password }}" 
                                                           readonly 
                                                           class="text-sm font-mono bg-gray-50 border border-gray-200 rounded px-3 py-2 w-32 focus:outline-none">
                                                    <button type="button" 
                                                            onclick="togglePassword({{ $admin->admin_id }})"
                                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                                        <svg id="eye-open-{{ $admin->admin_id }}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        <svg id="eye-closed-{{ $admin->admin_id }}" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 border-r border-gray-100">
                                            <div class="mb-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white" style="background-color: {{ $admin->getRoleColor() }}">
                                                    {{ $admin->role }}
                                                </span>
                                            </div>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach(array_slice($admin->privilege, 0, 3) as $privilege)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ str_replace('_', ' ', ucfirst($privilege)) }}
                                                    </span>
                                                @endforeach
                                                @if(count($admin->privilege) > 3)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                                        +{{ count($admin->privilege) - 3 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 border-r border-gray-100">
                                            {{ $admin->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-1">
                                                @canAccess('account_read')
                                                    <a href="{{ route('admin.management.show', $admin) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md inline-flex items-center text-xs transition-colors duration-200 justify-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Detail
                                                    </a>
                                                @else
                                                    <button class="@privilegeButton('account_read', 'melihat detail admin') bg-gray-400 text-white px-3 py-1 rounded-md inline-flex items-center text-xs justify-center">
                                                        <i class="fas fa-lock w-3 h-3 mr-1"></i>Detail
                                                    </button>
                                                @endcanAccess
                                                
                                                @canAccess('account_update')
                                                    <a href="{{ route('admin.management.edit', $admin) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md inline-flex items-center text-xs transition-colors duration-200 justify-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                @else
                                                    <button class="@privilegeButton('account_update', 'mengedit admin') bg-gray-400 text-white px-3 py-1 rounded-md inline-flex items-center text-xs justify-center">
                                                        <i class="fas fa-lock w-3 h-3 mr-1"></i>Edit
                                                    </button>
                                                @endcanAccess
                                                    
                                                @if($admin->admin_id !== auth('admin')->user()->admin_id)
                                                    @canAccess('account_delete')
                                                        <button type="button" 
                                                                onclick="openDeleteAdminModal('{{ $admin->admin_id }}', '{{ $admin->username }}', '{{ $admin->role }}', '{{ route('admin.management.destroy', $admin) }}')"
                                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md inline-flex items-center text-xs transition-colors duration-200 w-full sm:w-auto justify-center">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    @else
                                                        <button class="@privilegeButton('account_delete', 'menghapus admin') bg-gray-400 text-white px-3 py-1 rounded-md inline-flex items-center text-xs w-full sm:w-auto justify-center">
                                                            <i class="fas fa-lock w-3 h-3 mr-1"></i>Delete
                                                        </button>
                                                    @endcanAccess
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($admins->hasPages())
                        <div class="mt-6">
                            {{ $admins->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Admin Confirmation Modal -->
    <div id="deleteAdminModal" class="fixed inset-0 hidden" style="z-index: 99999;">
        <!-- Backdrop with blur -->
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" id="deleteAdminModalBackdrop" style="z-index: 99999;"></div>
        
        <!-- Modal -->
        <div class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 100000;">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full relative" id="deleteAdminModalContent" style="z-index: 100001;">
                <div class="p-6">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Confirmation Delete Admin
                        </h3>
                        <button type="button" onclick="closeDeleteAdminModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="mb-6">
                        <p class="text-gray-600 mb-3">Are you sure you want to delete this user?</p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-800 font-semibold" id="adminInfo">User Info</p>
                            <p class="text-red-600 text-sm mt-1">
                                User will lose access to the system and will not be able to log in again. This action cannot be undone.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeDeleteAdminModal()"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 border border-gray-300 rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                        <form id="deleteAdminForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                                Yes, Delete User
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Admin Delete Modal Functions
        function openDeleteAdminModal(adminId, username, role, deleteUrl) {
            // Set admin information
            document.getElementById('adminInfo').textContent = `${username} (${role}) - ID: ${adminId}`;
            document.getElementById('deleteAdminForm').action = deleteUrl;
            
            // Show modal
            const modal = document.getElementById('deleteAdminModal');
            modal.classList.remove('hidden');
            
            // Add scale-in animation
            const modalContent = document.getElementById('deleteAdminModalContent');
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
            modalContent.style.transition = 'all 0.15s ease-out';
            
            setTimeout(() => {
                modalContent.style.transform = 'scale(1)';
                modalContent.style.opacity = '1';
            }, 10);
        }

        function closeDeleteAdminModal() {
            // Add scale-out animation
            const modalContent = document.getElementById('deleteAdminModalContent');
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
            modalContent.style.transition = 'all 0.15s ease-in';
            
            setTimeout(() => {
                document.getElementById('deleteAdminModal').classList.add('hidden');
                // Reset animations
                modalContent.style.transform = '';
                modalContent.style.opacity = '';
                modalContent.style.transition = '';
            }, 150);
        }

        // Close admin modal when clicking backdrop or pressing Escape
        document.addEventListener('DOMContentLoaded', function() {
            const deleteAdminModal = document.getElementById('deleteAdminModal');
            if (deleteAdminModal) {
                const adminModalBackdrop = document.getElementById('deleteAdminModalBackdrop');
                adminModalBackdrop.addEventListener('click', closeDeleteAdminModal);
                
                // Close admin modal with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !deleteAdminModal.classList.contains('hidden')) {
                        closeDeleteAdminModal();
                    }
                });
            }
        });

        function togglePassword(adminId) {
            const passwordInput = document.getElementById(`password-${adminId}`);
            const eyeOpen = document.getElementById(`eye-open-${adminId}`);
            const eyeClosed = document.getElementById(`eye-closed-${adminId}`);
            
            if (passwordInput.type === 'text') {
                passwordInput.type = 'password';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'text';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>