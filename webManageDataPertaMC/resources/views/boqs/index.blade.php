<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    BOQ Management
                </h2>
                <p class="text-sm text-gray-600 mt-0.5">Project: {{ $project->title_project }} ({{ $project->no_IO }})</p>
            </div>
            <div class="mb-4">
                <a href="{{ route('projects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Projects
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            @if($boq)
                <!-- BOQ Details -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-semibold text-gray-800">No. BOQ: {{ $boq->nomorBoq }}</h3>
                            <div class="flex gap-3">
                                <!-- Upload Excel Button -->
                                <a href="{{ route('projects.boq.upload', [$project, $boq]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    Upload Excel
                                </a>
                                
                                <button type="button" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium inline-flex items-center transition-colors duration-200"
                                        onclick="openDeleteBoqModal('{{ $boq->id }}', '{{ $boq->nomorBoq }}', '{{ route('projects.boq.destroy', [$project, $boq]) }}')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus BOQ
                                </button>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-2">Tanggal BOQ</h4>
                                <p class="text-gray-600">{{ $boq->date->format('d/m/Y') }}</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-2">Total Harga</h4>
                                <p class="text-2xl font-bold text-green-600">{{ $boq->formatted_total_harga }}</p>
                            </div>
                        </div>

                        <!-- Sections Table -->
                        @if($boq->sections && $boq->sections->count() > 0)
                            <div class="mb-6">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Sections Overview
                                        </h4>
                                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                            <span class="flex items-center">
                                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                                {{ $boq->sections->count() }} Sections
                                            </span>
                                            <span class="flex items-center">
                                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                                {{ $boq->sections->sum(function($section) { return $section->rootDetails->count(); }) }} Root Items
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <a href="{{ route('projects.boq.manage-details', [$project, $boq]) }}" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-5 py-2.5 rounded-lg text-sm font-semibold inline-flex items-center transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                                            <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                            </svg>
                                            <span>Kelola Detail</span>
                                        </a>
                                        <a href="{{ route('projects.boq.sections.create', [$project, $boq]) }}" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-5 py-2.5 rounded-lg text-sm font-semibold inline-flex items-center transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                                            <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            <span>Tambah Section</span>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Sections Table -->
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 table-auto">
                                            <thead class="bg-gradient-to-r from-red-50 to-blue-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">
                                                        Item / Section
                                                    </th>
                                                    <th class="px-6 py-3 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">
                                                        Quantity / Details
                                                    </th>
                                                    <th class="px-6 py-3 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">
                                                        Total Harga
                                                    </th>
                                                    <th class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider">
                                                        Actions
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($boq->sections as $loop_index => $section)
                                                    <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                                        <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0 h-8 w-8">
                                                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                                        <span class="text-sm font-medium text-blue-600">{{ $loop_index + 1 }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="ml-4">
                                                                    <div class="text-sm font-bold text-gray-900">
                                                                        {{ $section->nama }}
                                                                    </div>
                                                                    <div class="text-sm text-gray-500">
                                                                        ID: {{ $section->id }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                                            <div class="flex items-center">
                                                                @if($section->details && $section->details->count() > 0)
                                                                    <div class="flex items-center">
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                            {{ $section->details->count() }} items
                                                                        </span>
                                                                        <button onclick="toggleDetails({{ $section->id }})" 
                                                                                id="toggle-btn-{{ $section->id }}"
                                                                                class="ml-2 text-xs bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded transition-all duration-200 hover:shadow-sm">
                                                                            <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                                            </svg>
                                                                            View
                                                                        </button>
                                                                    </div>
                                                                @else
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                        No details
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                                            <div class="text-sm font-medium text-green-600">
                                                                {{ $section->formatted_total_harga }}
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                            <div class="flex items-center justify-center space-x-2">
                                                                <a href="{{ route('sections.details.index', [$project, $section]) }}" 
                                                                   class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-xs inline-flex items-center">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 11h16M4 15h16"></path>
                                                                    </svg>
                                                                    Details
                                                                </a>
                                                                <a href="{{ route('projects.boq.sections.edit', [$project, $boq, $section]) }}" 
                                                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs inline-flex items-center">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                    </svg>
                                                                    Edit
                                                                </a>
                                                                <button type="button" 
                                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs inline-flex items-center transition-colors duration-200" 
                                                                        onclick="openDeleteModal('{{ $section->id }}', '{{ $section->nama }}', '{{ route('projects.boq.sections.destroy', [$project, $boq, $section]) }}')">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                    </svg>
                                                                    Delete
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!-- Details Rows (Collapsible) -->
                                                    @if($section->details && $section->details->count() > 0)
                                                        @foreach($section->rootDetails as $detailIndex => $detail)
                                                            @include('partials.detail-table-row-parent-only', [
                                                                'detail' => $detail, 
                                                                'sectionId' => $section->id,
                                                                'project' => $project,
                                                                'sectionNumber' => $loop_index + 1,
                                                                'detailNumber' => $detailIndex + 1
                                                            ])
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons after sections -->
                            <div class="mt-6 flex gap-4 justify-left">
                                @if($boq->sections->count() > 0)
                                    <!-- Select section for adding detail -->
                                    <div class="flex items-left gap-2">
                                        <select id="section-selector" class="border border-gray-300 rounded-md px-5 py-2 font-medium">
                                            <option value="">Pilih Section</option>
                                            @foreach($boq->sections as $section)
                                                <option value="{{ $section->id }}">{{ $section->nama }}</option>
                                            @endforeach
                                        </select>
                                        <button onclick="addDetailToSection()" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md font-medium inline-flex items-left">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Tambah Detail
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- No Sections Yet -->
                            <div class="text-center py-8">
                                <div class="mb-4">
                                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-gray-600 mb-2">Belum Ada Section</h4>
                                <p class="text-gray-500 mb-6">Mulai dengan menambahkan section pertama untuk BOQ ini</p>
                                <a href="{{ route('projects.boq.sections.create', [$project, $boq]) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md font-medium inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Section
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- No BOQ exists - Show empty state -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                    <div class="p-6 text-center">
                        <div class="mb-6">
                            <svg class="w-20 h-20 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4">BOQ Masih Kosong</h3>
                        <p class="text-gray-600 mb-8">Project ini belum memiliki BOQ. Buat BOQ untuk memulai manajemen biaya project.</p>
                        
                        <a href="{{ route('projects.boq.create', $project) }}" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-8 py-3 rounded-md shadow-md transform hover:scale-105 transition-all duration-200 inline-flex items-center text-lg font-medium">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Masukkan BOQ
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 hidden" style="z-index: 99999;">
        <!-- Backdrop with blur -->
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" id="deleteModalBackdrop" style="z-index: 99999;"></div>
        
        <!-- Modal -->
        <div class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 100000;">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full relative" id="deleteModalContent" style="z-index: 100001;">
                <div class="p-6">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Konfirmasi Hapus Section
                        </h3>
                        <button type="button" onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="mb-6">
                        <p class="text-gray-600 mb-3">Anda yakin ingin menghapus section ini?</p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-800 font-semibold" id="sectionName">Nama Section</p>
                            <p class="text-red-600 text-sm mt-1">
                                ⚠️ Semua detail di dalam section ini juga akan dihapus dan tidak dapat dikembalikan
                            </p>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeDeleteModal()"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 border border-gray-300 rounded-lg transition-colors duration-200">
                            Batal
                        </button>
                        <form id="deleteForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                                Ya, Hapus Section
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete BOQ Confirmation Modal -->
    <div id="deleteBoqModal" class="fixed inset-0 hidden" style="z-index: 99999;">
        <!-- Backdrop with blur -->
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" id="deleteBoqModalBackdrop" style="z-index: 99999;"></div>
        
        <!-- Modal -->
        <div class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 100000;">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full relative" id="deleteBoqModalContent" style="z-index: 100001;">
                <div class="p-6">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Konfirmasi Hapus BOQ
                        </h3>
                        <button type="button" onclick="closeDeleteBoqModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="mb-6">
                        <p class="text-gray-600 mb-3">Anda yakin ingin menghapus BOQ ini?</p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-800 font-semibold" id="boqNumber">BOQ Number</p>
                            <p class="text-red-600 text-sm mt-1">
                                ⚠️ Seluruh data BOQ termasuk semua section dan detail akan terhapus permanen dan tidak dapat dikembalikan
                            </p>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeDeleteBoqModal()"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 border border-gray-300 rounded-lg transition-colors duration-200">
                            Batal
                        </button>
                        <form id="deleteBoqForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                                Ya, Hapus BOQ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addDetailToSection() {
            const sectionSelect = document.getElementById('section-selector');
            const sectionId = sectionSelect.value;
            
            if (!sectionId) {
                alert('Pilih section terlebih dahulu');
                return;
            }
            
            // Redirect to detail create page
            const projectId = '{{ $project->no_IO }}';
            const url = `/projects/${projectId}/sections/${sectionId}/details/create`;
            window.location.href = url;
        }
        
        function toggleDetails(sectionId) {
            const detailRows = document.querySelectorAll('.detail-row-' + sectionId);
            const toggleBtn = document.getElementById('toggle-btn-' + sectionId);
            
            // Check if details are currently hidden
            const isHidden = detailRows[0]?.classList.contains('hidden');
            
            if (isHidden) {
                // Show all parent detail rows for this section (sudah hanya parent saja dari backend)
                detailRows.forEach(row => {
                    row.classList.remove('hidden');
                });
                toggleBtn.innerHTML = '<svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>Hide';
                toggleBtn.classList.remove('bg-gray-200', 'hover:bg-gray-300');
                toggleBtn.classList.add('bg-blue-200', 'hover:bg-blue-300');
            } else {
                // Hide all detail rows for this section
                detailRows.forEach(row => {
                    row.classList.add('hidden');
                });
                toggleBtn.innerHTML = '<svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>View';
                toggleBtn.classList.remove('bg-blue-200', 'hover:bg-blue-300');
                toggleBtn.classList.add('bg-gray-200', 'hover:bg-gray-300');
            }
        }
        
        function toggleSubDetails(detailNo) {
            // Find all child rows of this detail
            const childRows = document.querySelectorAll('[id^="detail-row-"][id*="-' + detailNo + '."]');
            const toggleIcon = document.getElementById('toggle-icon-' + detailNo);
            
            if (childRows.length === 0) return;
            
            // Check if children are currently hidden
            const isHidden = childRows[0]?.classList.contains('hidden');
            
            if (isHidden) {
                // Show direct children only
                childRows.forEach(row => {
                    // Only show direct children (one level deeper)
                    const idParts = row.id.split('-');
                    const rowDetailNo = idParts[idParts.length - 1];
                    const parentNo = rowDetailNo.substring(0, rowDetailNo.lastIndexOf('.'));
                    
                    if (parentNo === detailNo.toString()) {
                        row.classList.remove('hidden');
                    }
                });
                
                // Update icon to expanded state
                toggleIcon.innerHTML = '<svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7 7"></path></svg>';
            } else {
                // Hide all children recursively
                childRows.forEach(row => {
                    row.classList.add('hidden');
                });
                
                // Update icon to collapsed state
                toggleIcon.innerHTML = '<svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
            }
        }

        // Delete Modal Functions
        function openDeleteModal(sectionId, sectionName, deleteUrl) {
            console.log('openDeleteModal called with:', sectionId, sectionName, deleteUrl);
            
            const modal = document.getElementById('deleteModal');
            const sectionNameElement = document.getElementById('sectionName');
            const deleteForm = document.getElementById('deleteForm');
            
            // Set section name
            sectionNameElement.textContent = sectionName;
            
            // Set form action
            deleteForm.action = deleteUrl;
            
            // Show modal with animation
            modal.classList.remove('hidden');
            
            // Trigger reflow to ensure the element is rendered
            modal.offsetHeight;
            
            // Add animation classes
            const modalContent = document.getElementById('deleteModalContent');
            modalContent.style.transform = 'scale(0.9)';
            modalContent.style.opacity = '0';
            
            setTimeout(() => {
                modalContent.style.transition = 'all 0.2s ease-out';
                modalContent.style.transform = 'scale(1)';
                modalContent.style.opacity = '1';
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const modalContent = document.getElementById('deleteModalContent');
            
            // Add closing animation
            modalContent.style.transition = 'all 0.2s ease-in';
            modalContent.style.transform = 'scale(0.9)';
            modalContent.style.opacity = '0';
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modalContent.style.transform = '';
                modalContent.style.opacity = '';
                modalContent.style.transition = '';
            }, 200);
        }

        // BOQ Delete Modal Functions
        function openDeleteBoqModal(boqId, boqNumber, deleteUrl) {
            // Set BOQ information
            document.getElementById('boqNumber').textContent = `BOQ - ${boqNumber}`;
            document.getElementById('deleteBoqForm').action = deleteUrl;
            
            // Show modal
            const modal = document.getElementById('deleteBoqModal');
            modal.classList.remove('hidden');
            
            // Add scale-in animation
            const modalContent = document.getElementById('deleteBoqModalContent');
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
            modalContent.style.transition = 'all 0.15s ease-out';
            
            setTimeout(() => {
                modalContent.style.transform = 'scale(1)';
                modalContent.style.opacity = '1';
            }, 10);
        }

        function closeDeleteBoqModal() {
            // Add scale-out animation
            const modalContent = document.getElementById('deleteBoqModalContent');
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
            modalContent.style.transition = 'all 0.15s ease-in';
            
            setTimeout(() => {
                document.getElementById('deleteBoqModal').classList.add('hidden');
                // Reset animations
                modalContent.style.transform = '';
                modalContent.style.opacity = '';
                modalContent.style.transition = '';
            }, 150);
        }

        // Close delete modal when clicking backdrop or pressing Escape
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                const modalBackdrop = document.getElementById('deleteModalBackdrop');
                modalBackdrop.addEventListener('click', closeDeleteModal);
                
                // Close modal with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
                        closeDeleteModal();
                    }
                });
            }

            // BOQ Delete Modal event listeners
            const deleteBoqModal = document.getElementById('deleteBoqModal');
            if (deleteBoqModal) {
                const boqModalBackdrop = document.getElementById('deleteBoqModalBackdrop');
                boqModalBackdrop.addEventListener('click', closeDeleteBoqModal);
                
                // Close BOQ modal with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !deleteBoqModal.classList.contains('hidden')) {
                        closeDeleteBoqModal();
                    }
                });
            }
        });
    </script>
</x-app-layout>