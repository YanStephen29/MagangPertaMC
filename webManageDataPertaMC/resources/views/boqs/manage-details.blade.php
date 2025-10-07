<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-800 leading-tight flex items-center">
                    <svg class="w-7 h-7 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    Kelola Detail BOQ
                </h2>
                <div class="mt-2 flex flex-col sm:flex-row sm:items-center gap-2 text-sm text-gray-600">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-8m-9 0h2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2H5z"></path>
                        </svg>
                        Project: {{ $project->title_project }}
                    </span>
                    <span class="hidden sm:inline text-gray-400">•</span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4z"></path>
                        </svg>
                        IO: {{ $project->no_IO }}
                    </span>
                    <span class="hidden sm:inline text-gray-400">•</span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        BOQ: {{ $boq->nomorBoq }}
                    </span>
                </div>
            </div>
            <div class="mb-4">
                <a href="{{ route('projects.boq.index', $project) }}" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali ke BOQ</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <!-- Modern Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg p-6 border border-blue-200 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-700 font-semibold mb-1">Total Sections</p>
                            <p class="text-3xl font-bold text-blue-900">{{ $sections->count() }}</p>
                        </div>
                        <div class="bg-blue-500 rounded-full p-4 shadow-md">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg p-6 border border-green-200 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-700 font-semibold mb-1">Total Details</p>
                            <p class="text-3xl font-bold text-green-900">{{ $sections->sum(function($section) { return $section->details->count(); }) }}</p>
                        </div>
                        <div class="bg-green-500 rounded-full p-4 shadow-md">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-lg p-6 border border-purple-200 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-700 font-semibold mb-1">Root Details</p>
                            <p class="text-3xl font-bold text-purple-900">{{ $sections->sum(function($section) { return $section->rootDetails->count(); }) }}</p>
                        </div>
                        <div class="bg-purple-500 rounded-full p-4 shadow-md">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl shadow-lg p-6 border border-amber-200 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-amber-700 font-semibold mb-1">Total Value</p>
                            <p class="text-2xl font-bold text-amber-900">{{ $boq->formatted_total_harga }}</p>
                        </div>
                        <div class="bg-amber-500 rounded-full p-4 shadow-md">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comprehensive Details Table -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                                Comprehensive Details View
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Hierarchical view of all sections and details</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <button onclick="expandAll()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2.5 rounded-lg text-sm font-semibold inline-flex items-center transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                                Expand All
                            </button>
                            <button onclick="collapseAll()" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-4 py-2.5 rounded-lg text-sm font-semibold inline-flex items-center transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                                Collapse All
                            </button>
                            
                            <!-- Download Dropdown -->
                            <div class="relative inline-block text-left">
                                <button type="button" onclick="toggleDownloadMenu()" id="downloadMenuButton" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-2.5 rounded-lg text-sm font-semibold inline-flex items-center transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-weight="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div id="downloadMenu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-lg shadow-xl bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                    <div class="py-1">
                                        <a href="{{ route('projects.boq.download.excel', [$project, $boq]) }}" class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download Excel
                                        </a>
                                        <a href="{{ route('projects.boq.download.pdf', [$project, $boq]) }}" class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-800 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download PDF
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    @if($sections && $sections->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-red-50 to-blue-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">Note</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">Quantity</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">Unit</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">Harga Satuan</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-red-700 uppercase tracking-wider">Total Biaya</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($sections as $sectionIndex => $section)
                                        {{-- Section Row --}}
                                        <tr class="section-row hover:bg-red-50 transition-colors duration-200 border-l-4 border-red-400">
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                                <div class="flex items-center">
                                                    @php
                                                        $rootDetails = $section->details->whereNull('parent_no');
                                                    @endphp
                                                    @if($rootDetails && $rootDetails->count() > 0)
                                                        <button class="toggle-section mr-3 p-1 text-gray-400 hover:text-red-600 hover:bg-red-100 rounded-full focus:outline-none transition-all duration-200"
                                                                data-section-id="{{ $section->id }}">
                                                            <svg class="w-4 h-4 transform transition-transform duration-200 expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                            </svg>
                                                        </button>
                                                    @else
                                                        <div class="w-8 mr-3"></div>
                                                    @endif
                                                    <div class="font-bold text-gray-900 text-base">
                                                        {{ $sectionIndex + 1 }}. {{ $section->nama }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                                <span class="text-sm text-gray-500">Section</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right border-r border-gray-200">
                                                <span class="text-sm text-gray-500">-</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center border-r border-gray-200">
                                                <span class="text-sm text-gray-500">-</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right border-r border-gray-200">
                                                <span class="text-sm text-gray-500">-</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm font-medium text-green-600">
                                                    {{ $section->formatted_total_harga }}
                                                </div>
                                            </td>
                                        </tr>

                                        {{-- Detail Rows - Only show root details (children will be shown recursively) --}}
                                        @if($section->details && $section->details->count() > 0)
                                            @php
                                                // Filter to get only root details (those without parent_no) and sort them
                                                $rootDetails = $section->details->whereNull('parent_no')->sortBy('no')->values();
                                            @endphp
                                            @if($rootDetails && $rootDetails->count() > 0)
                                                <tbody id="section-details-{{ $section->id }}" class="section-details hidden">
                                                    @foreach($rootDetails as $detailIndex => $detail)
                                                        @include('partials.manage-detail-row', [
                                                            'detail' => $detail,
                                                            'sectionIndex' => $sectionIndex + 1,
                                                            'detailIndex' => $detailIndex + 1,
                                                            'level' => 1,
                                                            'sectionId' => $section->id,
                                                            'parentNumber' => null
                                                        ])
                                                    @endforeach
                                                </tbody>
                                            @endif
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No Sections Found</h3>
                            <p class="mt-1 text-sm text-gray-500">This BOQ doesn't have any sections yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function expandAll() {
            document.querySelectorAll('.toggle-section').forEach(button => {
                const sectionId = button.dataset.sectionId;
                showSectionDetails(sectionId, button);
            });
            
            document.querySelectorAll('.detail-toggle').forEach(button => {
                const detailId = button.dataset.detailId;
                const sectionId = button.dataset.sectionId;
                showDetailChildren(detailId, sectionId, button);
            });
        }

        function collapseAll() {
            document.querySelectorAll('.toggle-section').forEach(button => {
                const sectionId = button.dataset.sectionId;
                hideSectionDetails(sectionId, button);
            });
            
            document.querySelectorAll('.detail-toggle').forEach(button => {
                const currentNumber = button.dataset.currentNumber;
                const sectionId = button.dataset.sectionId;
                hideDetailChildren(currentNumber, sectionId, button);
            });
        }

        // Section and Detail toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Section toggle handlers
            document.querySelectorAll('.toggle-section').forEach(button => {
                button.addEventListener('click', function() {
                    const sectionId = this.dataset.sectionId;
                    const detailsContainer = document.getElementById(`section-details-${sectionId}`);
                    const icon = this.querySelector('.expand-icon');
                    
                    if (detailsContainer) {
                        if (detailsContainer.classList.contains('hidden')) {
                            detailsContainer.classList.remove('hidden');
                            if (icon) icon.style.transform = 'rotate(90deg)';
                        } else {
                            detailsContainer.classList.add('hidden');
                            if (icon) icon.style.transform = 'rotate(0deg)';
                            // Also hide all child details
                            detailsContainer.querySelectorAll('.detail-children').forEach(child => {
                                child.classList.add('hidden');
                            });
                            // Reset all detail toggle icons
                            detailsContainer.querySelectorAll('.detail-toggle svg').forEach(toggleIcon => {
                                toggleIcon.style.transform = 'rotate(0deg)';
                            });
                        }
                    }
                });
            });

            // Detail toggle handlers
            document.querySelectorAll('.detail-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    const currentNumber = this.dataset.currentNumber;
                    const sectionId = this.dataset.sectionId;
                    const icon = this.querySelector('svg');
                    
                    // Find all direct children of this detail
                    const directChildren = document.querySelectorAll(`[data-section-id="${sectionId}"][data-parent="${currentNumber}"]`);
                    
                    if (directChildren.length > 0) {
                        const isHidden = directChildren[0].classList.contains('hidden');
                        
                        if (isHidden) {
                            // Show direct children
                            directChildren.forEach(child => {
                                child.classList.remove('hidden');
                            });
                            if (icon) icon.style.transform = 'rotate(90deg)';
                        } else {
                            // Hide all descendants (children, grandchildren, etc.)
                            directChildren.forEach(child => {
                                child.classList.add('hidden');
                                // Also hide all nested descendants
                                const childNumber = child.dataset.currentNumber;
                                document.querySelectorAll(`[data-section-id="${sectionId}"].detail-row`).forEach(descendant => {
                                    const descendantNumber = descendant.dataset.currentNumber;
                                    if (descendantNumber && descendantNumber.startsWith(childNumber + '.')) {
                                        descendant.classList.add('hidden');
                                        // Reset their toggle icons
                                        const descendantToggle = descendant.querySelector('.detail-toggle svg');
                                        if (descendantToggle) {
                                            descendantToggle.style.transform = 'rotate(0deg)';
                                        }
                                    }
                                });
                            });
                            if (icon) icon.style.transform = 'rotate(0deg)';
                        }
                    }
                });
            });
        });

        // Expand All functionality
        function expandAll() {
            // Show all section details
            document.querySelectorAll('.section-details').forEach(container => {
                container.classList.remove('hidden');
            });
            
            // Show all detail children
            document.querySelectorAll('.detail-children').forEach(row => {
                row.classList.remove('hidden');
            });
            
            // Rotate all toggle icons
            document.querySelectorAll('.toggle-section .expand-icon, .detail-toggle svg').forEach(icon => {
                icon.style.transform = 'rotate(90deg)';
            });
        }

        // Collapse All functionality
        function collapseAll() {
            // Hide all section details
            document.querySelectorAll('.section-details').forEach(container => {
                container.classList.add('hidden');
            });
            
            // Hide all detail children
            document.querySelectorAll('.detail-children').forEach(row => {
                row.classList.add('hidden');
            });
            
            // Reset all toggle icons
            document.querySelectorAll('.toggle-section .expand-icon, .detail-toggle svg').forEach(icon => {
                icon.style.transform = 'rotate(0deg)';
            });
        }

        // Download Menu Toggle functionality
        function toggleDownloadMenu() {
            const menu = document.getElementById('downloadMenu');
            menu.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const button = document.getElementById('downloadMenuButton');
            const menu = document.getElementById('downloadMenu');
            
            if (!button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>