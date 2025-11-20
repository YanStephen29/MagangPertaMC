<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                Request Project Data & Equipment
            </h2>
            @if(isset($admin))
                <div class="flex items-center space-x-4 text-sm">
                    <div class="text-right">
                        <p class="font-semibold text-gray-700">{{ $admin->username }}</p>
                        <p class="text-xs text-{{ $admin->getRoleColor() }}-600">{{ $admin->role }}</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-xs font-medium transition-colors duration-200">
                        📊 Dashboard
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-3 sm:p-6 text-gray-900">
                    <!-- Tambah Project Button -->
                    <div class="mb-4 flex justify-end">
                        @canAccess('project_create')
                            <a href="{{ route('projects.create') }}" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium py-2 px-4 rounded-md shadow-md transform hover:scale-105 transition-all duration-200 inline-flex items-center text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Project
                            </a>
                        @endcanAccess
                    </div>
                    
                    <!-- Search Form -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg border">
                        <form method="GET" action="{{ route('projects.index') }}" class="flex flex-col sm:flex-row gap-3 items-end">
                            <!-- Search Input -->
                            <div class="flex-1 min-w-0">
                                <label for="search" class="block text-xs font-medium text-gray-600 mb-1">🔍 Search Project</label>
                                <input type="text" name="search" id="search" value="{{ request('search') ?? '' }}"
                                       placeholder="Search by No I/O or Project Title..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 text-sm">
                            </div>
                            
                            <!-- Sort Select -->
                            <div class="w-full sm:w-40">
                                <label for="sort" class="block text-xs font-medium text-gray-600 mb-1">📊 Sort by</label>
                                <select name="sort" id="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 text-sm">
                                    <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Latest Date</option>
                                    <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Oldest Date</option>
                                    <option value="no_IO_asc" {{ request('sort') == 'no_IO_asc' ? 'selected' : '' }}>No I/O (A-Z)</option>
                                    <option value="no_IO_desc" {{ request('sort') == 'no_IO_desc' ? 'selected' : '' }}>No I/O (Z-A)</option>
                                    <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Title (A-Z)</option>
                                    <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Title (Z-A)</option>
                                </select>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-2 flex-shrink-0">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Search
                                </button>
                                <a href="{{ route('projects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                        @if(isset($projects) && $projects->count() > 0)
                            <div class="overflow-x-auto -mx-3 sm:mx-0">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gradient-to-r from-red-50 to-blue-50">
                                        <tr>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">No I/O</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">Project Title</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">Assigned To</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200 hidden md:table-cell">Date Created</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-red-700 uppercase tracking-wider">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($projects as $project)
                                            <tr class="hover:bg-red-50 transition-colors duration-150">
                                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-bold text-red-600 border-r border-gray-100">{{ $project->no_IO }}</td>
                                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm text-gray-900 border-r border-gray-100 break-words">{{ $project->title_project }}</td>
                                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm border-r border-gray-100">
                                                    @if($project->assignedTo)
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-6 w-6 mr-2">
                                                                <div class="h-6 w-6 rounded-full flex items-center justify-center text-white font-bold text-xs" style="background-color: {{ $project->assignedTo->getRoleColor() }}">
                                                                    {{ substr($project->assignedTo->username, 0, 2) }}
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="text-sm font-medium text-gray-900">{{ $project->assignedTo->username }}</div>
                                                                <div class="text-xs text-gray-500">{{ $project->assignedTo->role }}</div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 text-sm">Not Assigned</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 border-r border-gray-100 hidden md:table-cell">{{ $project->created_at->format('d/m/Y') }}</td>
                                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex gap-2">
                                                        <!-- Tools Button - Always accessible if can read -->
                                                        @canAccess('tools_read')
                                                            @php
                                                                $boqStatus = $project->getBOQValidationStatus();
                                                            @endphp
                                                            @if($boqStatus['status'] === 'valid')
                                                                <a href="{{ route('projects.tools.index', $project) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs inline-flex items-center justify-center">
                                                                    <span class="leading-none">Request</span>
                                                                </a>
                                                            @else
                                                                <button onclick="showBOQValidationModal('{{ $boqStatus['status'] }}', '{{ $boqStatus['message'] }}', '{{ $project->no_IO }}', '{{ $boqStatus['action'] }}')" 
                                                                        class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1 rounded text-xs inline-flex items-center justify-center">
                                                                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                                    </svg>
                                                                    <span class="leading-none">Request</span>
                                                                </button>
                                                            @endif
                                                        @else
                                                            <button class="@privilegeButton('tools_read', 'melihat tools project') bg-gray-400 text-white px-3 py-1 rounded text-xs">
                                                                <i class="fas fa-lock mr-1"></i>Tools
                                                            </button>
                                                        @endcanAccess

                                                        <!-- BOQ Button - Only for Admin/PM with access -->
                                                        @if(auth()->guard('admin')->check() && in_array(auth()->guard('admin')->user()->role, ['Admin', 'Project Manager']))
                                                            @if($project->canAccessBOQ(auth()->guard('admin')->user()))
                                                                <a href="{{ route('projects.boq.index', $project) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">BOQ</a>
                                                            @endif
                                                        @endif

                                                        <!-- Assign Button - Only for Admin management -->
                                                        @canAccess('account_update')
                                                            <a href="{{ route('projects.assign.show', $project) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-xs">Assign</a>
                                                        @endcanAccess
                                                        
                                                        <!-- Edit Button -->
                                                        @canAccess('project_update')
                                                            @if($project->canBeManaged($admin))
                                                                <a href="{{ route('projects.edit', $project) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">Edit</a>
                                                            @endif
                                                        @endcanAccess
                                                        
                                                        
                                                        <!-- Delete Button -->
                                                        @canAccess('project_delete')
                                                            @if($project->canBeManaged($admin))
                                                                <button type="button" 
                                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs"
                                                                        onclick="openDeleteModal('{{ $project->no_IO }}', '{{ $project->title_project }}')">
                                                                    Delete
                                                                </button>
                                                            @endif
                                                        @endcanAccess
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
        @else
            <!-- Empty Data State -->
            @if($projects->count() === 0 && !request('search'))
                @if($admin->role === 'Project Manager')
                    @emptyDataState('Tidak Ada Project yang Di-assign', 'Belum ada project yang di-assign kepada Anda oleh Admin. Hubungi Admin untuk mendapatkan assignment project.')
                @else
                    @emptyDataState('Tidak Ada Data Project', 'Belum ada project yang dibuat dalam sistem.')
                @endif
            @else
                <div class="text-center py-12">
                    <div class="text-6xl text-gray-300 mb-4">
                        <i class="fas fa-search"></i>
                    </div>
                    <p class="text-xl text-gray-500 mb-6">
                        No Project Found with Keyword "{{ request('search') }}"
                    </p>
                    <a href="{{ route('projects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-md">
                        Back to All Projects
                    </a>
                </div>
            @endif
        @endif

                        <!-- Quick Links -->
                        <div class="mt-8 flex gap-4">
                            @canAccess('bidang_read')
                                <a href="{{ route('bidangs.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">📊 Manage GL Code</a>
                            @else
                                <button class="@privilegeButton('bidang_read', 'mengelola kode bidang') bg-gray-400 text-white px-4 py-2 rounded text-sm">
                                    <i class="fas fa-lock mr-1"></i> 📊 Manage GL Code
                                </button>
                            @endcanAccess
                            
                            @canAccess('document_read')
                                <a href="{{ route('documents.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm">📄 Manage Documents</a>
                            @else
                                <button class="@privilegeButton('document_read', 'mengelola documents') bg-gray-400 text-white px-4 py-2 rounded text-sm">
                                    <i class="fas fa-lock mr-1"></i> 📄 Manage Documents
                                </button>
                            @endcanAccess
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 hidden" style="z-index: 99999;">
        <!-- Backdrop with blur -->
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" id="modalBackdrop" style="z-index: 99999;"></div>
        
        <!-- Modal -->
        <div class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 100000;">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full relative" id="modalContent" style="z-index: 100001;">
                <div class="p-6">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Confirm Project Delete
                        </h3>
                        <button type="button" onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="mb-6">
                        <p class="text-gray-600 mb-3">Are you sure you want to delete this project?</p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-800 font-semibold" id="projectName">Test Project Name</p>
                            <p class="text-red-600 text-sm mt-1">
                                ⚠️ Deleted data cannot be recovered
                            </p>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeDeleteModal()"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 border border-gray-300 rounded-lg">
                            Cancel
                        </button>
                        <form id="deleteForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                                Yes, Delete Project
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BOQ Validation Modal -->
    <div id="boqValidationModal" class="fixed inset-0 hidden" style="z-index: 99999;">
        <!-- Backdrop with blur -->
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" id="boqModalBackdrop" style="z-index: 99999;"></div>
        
        <!-- Modal -->
        <div class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 100000;">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full relative" id="boqModalContent" style="z-index: 100001;">
                <div class="p-6">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Validasi BOQ Required
                        </h3>
                        <button type="button" onclick="closeBOQValidationModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="mb-6">
                        <div id="boqWarningBox" class="border rounded-lg p-4 mb-4">
                            <p id="boqValidationMessage" class="text-gray-700 mb-3"></p>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeBOQValidationModal()"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 border border-gray-300 rounded-lg">
                            Cancel
                        </button>
                        <button id="boqActionButton" 
                                type="button"
                                class="px-4 py-2 text-white rounded-lg">
                            Action Button
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(projectNoIO, projectName) {
            console.log('openDeleteModal called with:', projectNoIO, projectName);
            
            const modal = document.getElementById('deleteModal');
            if (!modal) {
                console.error('Modal element not found!');
                return;
            }
            
            const projectNameElement = document.getElementById('projectName');
            if (projectNameElement) {
                projectNameElement.textContent = projectName;
            }
            
            const deleteForm = document.getElementById('deleteForm');
            if (deleteForm) {
                deleteForm.action = '/projects/' + projectNoIO;
            }
            
            // Show modal
            modal.classList.remove('hidden');
            modal.style.display = 'block';
        }
        
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
            }
        }
        
        function showBOQValidationModal(status, message, projectNoIO, action) {
            const modal = document.getElementById('boqValidationModal');
            const warningBox = document.getElementById('boqWarningBox');
            const messageElement = document.getElementById('boqValidationMessage');
            const actionButton = document.getElementById('boqActionButton');
            
            if (!modal) return;
            
            // Set message
            messageElement.textContent = message;
            
            // Configure warning box and action button based on status
            if (status === 'no_boq') {
                warningBox.className = 'bg-red-50 border border-red-200 rounded-lg p-4 mb-4';
                messageElement.className = 'text-red-800 mb-3';
                actionButton.className = 'px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg';
                actionButton.textContent = 'Buat BOQ';
                actionButton.onclick = function() {
                    // Use Laravel route helper to generate correct URL
                    window.location.href = '{{ route("projects.boq.create", ":projectId") }}'.replace(':projectId', projectNoIO);
                };
            } else if (status === 'empty_boq') {
                warningBox.className = 'bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4';
                messageElement.className = 'text-yellow-800 mb-3';
                actionButton.className = 'px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg';
                actionButton.textContent = 'Isi BOQ';
                actionButton.onclick = function() {
                    // Use Laravel route helper to generate correct URL
                    window.location.href = '{{ route("projects.boq.index", ":projectId") }}'.replace(':projectId', projectNoIO);
                };
            }
            
            // Show modal
            modal.classList.remove('hidden');
            modal.style.display = 'block';
        }
        
        function closeBOQValidationModal() {
            const modal = document.getElementById('boqValidationModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modalBackdrop = document.getElementById('modalBackdrop');
            if (modalBackdrop) {
                modalBackdrop.addEventListener('click', closeDeleteModal);
            }
            
            const boqModalBackdrop = document.getElementById('boqModalBackdrop');
            if (boqModalBackdrop) {
                boqModalBackdrop.addEventListener('click', closeBOQValidationModal);
            }
            
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeDeleteModal();
                    closeBOQValidationModal();
                }
            });
        });
    </script>
</x-app-layout>
