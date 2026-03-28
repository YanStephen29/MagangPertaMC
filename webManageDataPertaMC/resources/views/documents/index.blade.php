<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            Manajemen Documents
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-3 sm:p-6 text-gray-900">
                    <!-- Search and Filter Section -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg border">
                        <!-- Summary Stats -->
                        <div class="mb-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                            @php
                                $totalDocs = \App\Models\Document::count();
                                $materialRequests = \App\Models\Document::where('jenis_request', 'Material Request')->count();
                                $serviceRequests = \App\Models\Document::where('jenis_request', 'Service Request')->count();
                                $facilityRequests = \App\Models\Document::where('jenis_request', 'Facility Request')->count();
                            @endphp
                            
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <div class="text-blue-600 text-sm font-medium">Total Documents</div>
                                <div class="text-2xl font-bold text-blue-900">{{ $totalDocs }}</div>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <div class="text-green-600 text-sm font-medium">Material Request</div>
                                <div class="text-2xl font-bold text-green-900">{{ $materialRequests }}</div>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                <div class="text-yellow-600 text-sm font-medium">Service Request</div>
                                <div class="text-2xl font-bold text-yellow-900">{{ $serviceRequests }}</div>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                                <div class="text-purple-600 text-sm font-medium">Facility Request</div>
                                <div class="text-2xl font-bold text-purple-900">{{ $facilityRequests }}</div>
                            </div>
                        </div>
                        
                        <form method="GET" action="{{ route('documents.index') }}" class="space-y-4">
                            <div class="flex flex-col lg:flex-row gap-4 items-end">
                                <!-- Search Input -->
                                <div class="flex-1">
                                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                        🔍 Search Document
                                    </label>
                                    <input type="text" 
                                           name="search" 
                                           id="search"
                                           value="{{ request('search') }}"
                                           placeholder="Search by request number or type..."
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 text-sm">
                                </div>
                                
                                <!-- Filter by Jenis Request -->
                                <div class="w-full lg:w-48">
                                    <label for="jenis_request" class="block text-sm font-medium text-gray-700 mb-2">
                                        📋 Type Request
                                    </label>
                                    <select name="jenis_request" 
                                            id="jenis_request"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 text-sm">
                                        <option value="">All Types</option>
                                        <option value="Material Request" {{ request('jenis_request') == 'Material Request' ? 'selected' : '' }}>Material Request</option>
                                        <option value="Service Request" {{ request('jenis_request') == 'Service Request' ? 'selected' : '' }}>Service Request</option>
                                        <option value="Facility Request" {{ request('jenis_request') == 'Facility Request' ? 'selected' : '' }}>Facility Request</option>
                                        <option value="Aset" {{ request('jenis_request') == 'Aset' ? 'selected' : '' }}>Aset</option>
                                    </select>
                                </div>
                                
                                <!-- Sort By -->
                                <div class="w-full lg:w-48">
                                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
                                        📊 Sort By
                                    </label>
                                    <select name="sort" 
                                            id="sort"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 text-sm">
                                        <option value="date_issue_desc" {{ request('sort') == 'date_issue_desc' ? 'selected' : '' }}>Latest Date</option>
                                        <option value="date_issue_asc" {{ request('sort') == 'date_issue_asc' ? 'selected' : '' }}>Oldest Date</option>
                                        <option value="no_request_asc" {{ request('sort') == 'no_request_asc' ? 'selected' : '' }}>No Request (A-Z)</option>
                                        <option value="no_request_desc" {{ request('sort') == 'no_request_desc' ? 'selected' : '' }}>No Request (Z-A)</option>
                                        <option value="jenis_asc" {{ request('sort') == 'jenis_asc' ? 'selected' : '' }}>Type (A-Z)</option>
                                        <option value="jenis_desc" {{ request('sort') == 'jenis_desc' ? 'selected' : '' }}>Type (Z-A)</option>
                                    </select>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-sm transition-colors duration-200 text-sm font-medium inline-flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        Search
                                    </button>
                                    
                                    <a href="{{ route('documents.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow-sm transition-colors duration-200 text-sm font-medium inline-flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Reset
                                    </a>
                                    
                                    <a href="{{ route('documents.create') }}" class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-4 py-2 rounded-md shadow-md transform hover:scale-105 transition-all duration-200 text-sm font-medium inline-flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Add Document
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Notifikasi sudah ditangani di layout utama (app.blade.php) -->

                    @if($documents->count() > 0)
                        <div class="overflow-x-auto -mx-3 sm:mx-0">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-purple-50 to-pink-50">
                                    <tr>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-purple-700 uppercase tracking-wider border-r border-gray-200">
                                            No Request
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-purple-700 uppercase tracking-wider border-r border-gray-200">
                                            Type Request
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-purple-700 uppercase tracking-wider border-r border-gray-200 hidden md:table-cell">
                                            Date Issue
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-purple-700 uppercase tracking-wider border-r border-gray-200">
                                            Related Tools
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-purple-700 uppercase tracking-wider border-r border-gray-200 hidden lg:table-cell">
                                            Status Stage
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-purple-700 uppercase tracking-wider">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($documents as $document)
                                        <tr class="hover:bg-purple-50 transition-colors duration-150">
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-bold text-purple-600 border-r border-gray-100">
                                                {{ $document->no_request }}
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm border-r border-gray-100">
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @if($document->jenis_request == 'Material Request') bg-blue-100 text-blue-800
                                                    @elseif($document->jenis_request == 'Service Request') bg-green-100 text-green-800
                                                    @elseif($document->jenis_request == 'Facility Request') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $document->jenis_request }}
                                                </span>
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 border-r border-gray-100 hidden md:table-cell">
                                                {{ $document->date_issue->format('d/m/Y') }}
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 border-r border-gray-100">
                                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">
                                                    {{ $document->tools->count() }} tools
                                                </span>
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm border-r border-gray-100 hidden lg:table-cell">
                                                @php
                                                    $currentTahapan = $document->getCurrentTahapan();
                                                    $progressPercentage = $document->getProgressPercentage();
                                                @endphp
                                                
                                                @if($currentTahapan)
                                                    <div class="flex items-center space-x-2">
                                                        <div class="flex-shrink-0">
                                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium
                                                                {{ $currentTahapan->Date_Tahapan ? 'bg-green-500 text-white' : 'bg-yellow-400 text-white' }}">
                                                                {{ $currentTahapan->Date_Tahapan ? '✓' : '⏳' }}
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="text-xs font-medium text-gray-900 truncate">
                                                                {{ Str::limit($currentTahapan->namaTahapan, 20) }}
                                                            </div>
                                                            <div class="flex items-center mt-1">
                                                                <div class="w-12 bg-gray-200 rounded-full h-1.5 mr-2">
                                                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                                                                </div>
                                                                <span class="text-xs text-gray-500">{{ $progressPercentage }}%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="flex items-center text-gray-400">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <span class="text-xs">Hasn't Started</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-1">
                                                    <a href="{{ route('documents.show', ['document' => base64_encode($document->no_request)]) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-md inline-flex items-center text-xs transition-colors duration-200 justify-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 616 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Detail
                                                    </a>
                                                    <a href="{{ route('documents.edit', ['document' => base64_encode($document->no_request)]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md inline-flex items-center text-xs transition-colors duration-200 justify-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <button type="button" 
                                                            onclick="openDeleteDocumentModal('{{ base64_encode($document->no_request) }}', '{{ $document->no_request }}', '{{ route('documents.destroy', ['document' => base64_encode($document->no_request)]) }}')"
                                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md inline-flex items-center text-xs transition-colors duration-200 w-full sm:w-auto justify-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $documents->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mb-4">
                                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-xl text-gray-500 mb-6">
                                @if(request('search') || request('jenis_request'))
                                    No documents found matching the search criteria.
                                @else
                                    There are no documents yet.
                                @endif
                            </p>
                            <a href="{{ route('documents.create') }}" class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-medium py-2 px-6 rounded-md shadow-md transform hover:scale-105 transition-all duration-200 inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add First Document
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Document Confirmation Modal -->
    <div id="deleteDocumentModal" class="fixed inset-0 hidden" style="z-index: 99999;">
        <!-- Backdrop with blur -->
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" id="deleteDocumentModalBackdrop" style="z-index: 99999;"></div>
        
        <!-- Modal -->
        <div class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 100000;">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full relative" id="deleteDocumentModalContent" style="z-index: 100001;">
                <div class="p-6">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Confirm Delete Document
                        </h3>
                        <button type="button" onclick="closeDeleteDocumentModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="mb-6">
                        <p class="text-gray-600 mb-3">Are you sure you want to delete this document?</p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-800 font-semibold" id="documentNumber">Document Number</p>
                            <p class="text-red-600 text-sm mt-1">
                                All document data including all tools and stages will be permanently deleted and cannot be recovered !
                            </p>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeDeleteDocumentModal()"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 border border-gray-300 rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                        <form id="deleteDocumentForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                                Yes, Delete Document
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Document Delete Modal Functions
        function openDeleteDocumentModal(documentId, documentNumber, deleteUrl) {
            // Set document information
            document.getElementById('documentNumber').textContent = `Document - ${documentNumber}`;
            document.getElementById('deleteDocumentForm').action = deleteUrl;
            
            // Show modal
            const modal = document.getElementById('deleteDocumentModal');
            modal.classList.remove('hidden');
            
            // Add scale-in animation
            const modalContent = document.getElementById('deleteDocumentModalContent');
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
            modalContent.style.transition = 'all 0.15s ease-out';
            
            setTimeout(() => {
                modalContent.style.transform = 'scale(1)';
                modalContent.style.opacity = '1';
            }, 10);
        }

        function closeDeleteDocumentModal() {
            // Add scale-out animation
            const modalContent = document.getElementById('deleteDocumentModalContent');
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
            modalContent.style.transition = 'all 0.15s ease-in';
            
            setTimeout(() => {
                document.getElementById('deleteDocumentModal').classList.add('hidden');
                // Reset animations
                modalContent.style.transform = '';
                modalContent.style.opacity = '';
                modalContent.style.transition = '';
            }, 150);
        }

        // Close document modal when clicking backdrop or pressing Escape
        document.addEventListener('DOMContentLoaded', function() {
            const deleteDocumentModal = document.getElementById('deleteDocumentModal');
            if (deleteDocumentModal) {
                const documentModalBackdrop = document.getElementById('deleteDocumentModalBackdrop');
                documentModalBackdrop.addEventListener('click', closeDeleteDocumentModal);
                
                // Close document modal with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !deleteDocumentModal.classList.contains('hidden')) {
                        closeDeleteDocumentModal();
                    }
                });
            }
        });
    </script>
</x-app-layout>