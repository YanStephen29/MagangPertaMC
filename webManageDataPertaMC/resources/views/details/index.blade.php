<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-800 leading-tight flex items-center">
                    <svg class="w-7 h-7 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    Manage Detail Section
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Section: {{ $section->nama }}
                    </span>
                    <span class="hidden sm:inline text-gray-400">•</span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        BOQ: {{ $section->boq->nomorBoq }}
                    </span>
                </div>
            </div>
            <div class="mb-4">
                <a href="{{ route('projects.boq.index', $project) }}" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to BOQ</span>
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
                            <p class="text-sm text-blue-700 font-semibold mb-1">Current Section</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $section->nama }}</p>
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
                            <p class="text-3xl font-bold text-green-900">{{ $section->details->count() }}</p>
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
                            <p class="text-sm text-purple-700 font-semibold mb-1">Sections Details</p>
                            <p class="text-3xl font-bold text-purple-900">{{ $details->count() }}</p>
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
                            <p class="text-sm text-amber-700 font-semibold mb-1">Total Budget</p>
                            <p class="text-2xl font-bold text-amber-900">{{ $section->formatted_total_harga }}</p>
                        </div>
                        <div class="bg-amber-500 rounded-full p-4 shadow-md">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
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
                                Section Details: {{ $section->nama }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Hierarchical view of section details</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('sections.details.create', [$project, $section]) }}" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-4 py-2.5 rounded-lg text-sm font-semibold inline-flex items-center transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Detail
                            </a>
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
                        </div>
                    </div>

                    @if($details && $details->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-red-50 to-blue-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">Note</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">Quantity</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">Unit</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">Unit/Price</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">Total Cost</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @include('partials.detail-table-rows-full', [
                                        'details' => $details, 
                                        'level' => 0, 
                                        'project' => $project, 
                                        'section' => $section,
                                        'parentNumber' => null
                                    ])
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
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No Details Found</h3>
                            <p class="mt-1 text-sm text-gray-500">This section doesn't have any details yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="fixed bottom-4 right-4 md:bottom-8 md:right-8 z-50">
        <div class="relative">
            <!-- Main FAB Button -->
            <button id="fab-main" class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-full shadow-2xl transform hover:scale-110 transition-all duration-300 flex items-center justify-center focus:outline-none focus:ring-4 focus:ring-red-300">
                <svg id="fab-icon" class="w-5 h-5 md:w-6 md:h-6 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </button>

            <!-- Expandable Menu Items -->
            <div id="fab-menu" class="absolute bottom-20 right-0 space-y-3 opacity-0 invisible transform scale-95 transition-all duration-300">
                <!-- Add Detail -->
                <a href="{{ route('sections.details.create', [$project, $section]) }}" 
                   class="group flex items-center justify-end space-x-3">
                    <span class="bg-white text-gray-800 px-4 py-2 rounded-lg shadow-lg text-sm font-medium whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        Add New Detail
                    </span>
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transform hover:scale-110 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </a>

                <!-- Add Section -->
                <a href="{{ route('projects.boq.sections.create', [$project, $section->boq]) }}" 
                   class="group flex items-center justify-end space-x-3">
                    <span class="bg-white text-gray-800 px-4 py-2 rounded-lg shadow-lg text-sm font-medium whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        Add New Section
                    </span>
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-full shadow-lg flex items-center justify-center transform hover:scale-110 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </a>

                <!-- Back to BOQ -->
                <a href="{{ route('projects.boq.index', $project) }}" 
                   class="group flex items-center justify-end space-x-3">
                    <span class="bg-white text-gray-800 px-4 py-2 rounded-lg shadow-lg text-sm font-medium whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        Back to BOQ
                    </span>
                    <div class="w-12 h-12 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white rounded-full shadow-lg flex items-center justify-center transform hover:scale-110 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle expand/collapse functionality for sections
            document.querySelectorAll('.toggle-section').forEach(button => {
                button.addEventListener('click', function() {
                    const sectionId = this.dataset.sectionId;
                    const detailsContainer = document.getElementById(`details-${sectionId}`);
                    const icon = this.querySelector('.expand-icon');
                    
                    if (detailsContainer.classList.contains('hidden')) {
                        detailsContainer.classList.remove('hidden');
                        if (icon) icon.style.transform = 'rotate(90deg)';
                    } else {
                        detailsContainer.classList.add('hidden');
                        if (icon) icon.style.transform = 'rotate(0deg)';
                    }
                });
            });

            // Handle detail expand/collapse functionality
            document.querySelectorAll('.detail-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    const currentRow = this.closest('[data-section-id]');
                    const sectionId = currentRow.dataset.sectionId;
                    const currentNumber = currentRow.dataset.currentNumber;
                    const icon = this.querySelector('svg');
                    
                    // Find direct children (next level only)
                    const directChildren = Array.from(document.querySelectorAll(`[data-section-id="${sectionId}"].detail-row`))
                        .filter(row => {
                            const rowNumber = row.dataset.currentNumber;
                            if (!rowNumber) return false;
                            
                            // Check if this is a direct child (one level deeper)
                            const currentParts = currentNumber.split('.');
                            const rowParts = rowNumber.split('.');
                            
                            // Must be exactly one level deeper
                            if (rowParts.length !== currentParts.length + 1) return false;
                            
                            // Must start with current number
                            return rowNumber.startsWith(currentNumber + '.');
                        });
                    
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

            // Enhanced toggle children functionality (for backward compatibility)
            document.querySelectorAll('.toggle-children').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const targetElement = document.getElementById(targetId);
                    const icon = this.querySelector('.expand-icon');
                    
                    if (targetElement) {
                        targetElement.classList.toggle('hidden');
                        if (icon) {
                            icon.classList.toggle('rotate-90');
                        }
                    }
                });
            });

            // Floating Action Button functionality
            const fabMain = document.getElementById('fab-main');
            const fabMenu = document.getElementById('fab-menu');
            const fabIcon = document.getElementById('fab-icon');
            let isMenuOpen = false;

            if (fabMain && fabMenu && fabIcon) {
                fabMain.addEventListener('click', function() {
                    isMenuOpen = !isMenuOpen;
                    
                    if (isMenuOpen) {
                        // Open menu
                        fabMenu.classList.remove('opacity-0', 'invisible', 'scale-95');
                        fabMenu.classList.add('opacity-100', 'visible', 'scale-100');
                        
                        // Rotate main button icon
                        fabIcon.style.transform = 'rotate(45deg)';
                        
                        // Change main button style
                        fabMain.classList.add('bg-gradient-to-r', 'from-red-700', 'to-red-800');
                    } else {
                        // Close menu
                        fabMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                        fabMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                        
                        // Reset main button icon
                        fabIcon.style.transform = 'rotate(0deg)';
                        
                        // Reset main button style
                        fabMain.classList.remove('from-red-700', 'to-red-800');
                    }
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!fabMain.contains(event.target) && !fabMenu.contains(event.target) && isMenuOpen) {
                        isMenuOpen = false;
                        fabMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                        fabMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                        fabIcon.style.transform = 'rotate(0deg)';
                        fabMain.classList.remove('from-red-700', 'to-red-800');
                    }
                });
            }
        });

        // Expand All functionality
        function expandAll() {
            // Show all section details
            document.querySelectorAll('.section-details').forEach(container => {
                container.classList.remove('hidden');
            });
            
            // Show all detail children (both old and new structure)
            document.querySelectorAll('.detail-children, [id^="children-"]').forEach(row => {
                row.classList.remove('hidden');
            });
            
            // Rotate all toggle icons
            document.querySelectorAll('.toggle-section .expand-icon, .detail-toggle svg').forEach(icon => {
                icon.style.transform = 'rotate(90deg)';
            });
            
            // For backward compatibility
            document.querySelectorAll('.expand-icon').forEach(icon => {
                icon.classList.add('rotate-90');
            });
        }

        // Collapse All functionality
        function collapseAll() {
            // Hide all section details
            document.querySelectorAll('.section-details').forEach(container => {
                container.classList.add('hidden');
            });
            
            // Hide all detail children (both old and new structure)
            document.querySelectorAll('.detail-children, [id^="children-"]').forEach(row => {
                row.classList.add('hidden');
            });
            
            // Reset all toggle icons
            document.querySelectorAll('.toggle-section .expand-icon, .detail-toggle svg').forEach(icon => {
                icon.style.transform = 'rotate(0deg)';
            });
            
            // For backward compatibility
            document.querySelectorAll('.expand-icon').forEach(icon => {
                icon.classList.remove('rotate-90');
            });
        }

        function toggleFullSpec(detailNo) {
            const fullSpec = document.getElementById('full-spec-' + detailNo);
            if (fullSpec) {
                fullSpec.classList.toggle('hidden');
            }
        }
    </script>

    <!-- Floating Action Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <div class="group relative">
            <!-- Add Detail Button -->
            <a href="{{ route('sections.details.create', [$project, $section]) }}" 
               class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-full p-4 shadow-2xl transform hover:scale-110 transition-all duration-300 flex items-center justify-center group">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </a>
            <!-- Tooltip -->
            <div class="absolute bottom-full right-0 mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap">
                Add New Detail
            </div>
        </div>
    </div>
</x-app-layout>