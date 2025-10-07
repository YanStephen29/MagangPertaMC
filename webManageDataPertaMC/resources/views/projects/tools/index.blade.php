<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    {{ $project->title_project }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">No I/O: {{ $project->no_IO }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-3 sm:p-6 text-gray-900">
                    <!-- Navigation & Assign Controls -->
                    @if($tools->count() > 0)
                        <!-- Initial State: Back Button + Assign Button -->
                        <div id="initialAssignSection" class="mb-4 flex justify-between items-center">
                            <a href="{{ route('projects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Projects
                            </a>
                            <button type="button" onclick="enterBulkMode()" 
                                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-md text-sm font-medium transition-colors shadow-md hover:shadow-lg inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Assign to Document
                            </button>
                        </div>

                        <!-- Bulk Assignment Controls (Hidden Initially) -->
                        <div id="bulkAssignSection" class="mb-4 bg-gradient-to-r from-purple-50 to-blue-50 p-4 rounded-lg border border-purple-200 hidden">
                            <div class="flex justify-between items-start mb-4">
                                <a href="{{ route('projects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Back to Projects
                                </a>
                                <button type="button" onclick="exitBulkMode()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                    Cancel Bulk Mode
                                </button>
                            </div>
                            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-purple-800 mb-1"> Bulk Assignment Mode Active</h3>
                                    <p class="text-sm text-purple-600">Select the requests you want to assign to the same document</p>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                                    <button id="selectAllBtn" type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                        Select All
                                    </button>
                                    <button id="clearSelectionBtn" type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                        Clear Selection
                                    </button>
                                    <button id="bulkAssignBtn" type="button" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                                        Assign Selected
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Selected Count -->
                            <div id="selectedCount" class="mt-3 text-sm text-purple-700">
                                <span class="font-medium">0 request selected</span>
                            </div>
                        </div>
                    @else
                        <!-- When no tools, show back button with consistent styling -->
                        <div class="mb-4 flex justify-between items-center">
                            <a href="{{ route('projects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Projects
                            </a>
                            <a href="{{ route('projects.tools.create', $project) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add First Tool
                            </a>
                        </div>
                    @endif

                    <!-- Search and Filter Section -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg border">
                        <form method="GET" action="{{ route('projects.tools.index', $project) }}" class="space-y-4">
                            <div class="flex flex-col lg:flex-row gap-4 items-end">
                                <!-- Search Input -->
                                <div class="flex-1">
                                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                        🔍 Search Request
                                    </label>
                                    <input type="text" 
                                           name="search" 
                                           id="search"
                                           value="{{ request('search') }}"
                                           placeholder="Search by description or unit..."
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 text-sm">
                                </div>
                                
                                <!-- Document Filter -->
                                <div class="w-full lg:w-48">
                                    <label for="document" class="block text-sm font-medium text-gray-700 mb-2">
                                        Document
                                    </label>
                                    <select name="document" 
                                            id="document"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 text-sm">
                                        <option value="">All Document</option>
                                        @foreach($documents as $doc)
                                            <option value="{{ $doc->no_request }}" {{ request('document') == $doc->no_request ? 'selected' : '' }}>
                                                {{ $doc->no_request }}
                                            </option>
                                        @endforeach
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
                                    
                                    <a href="{{ route('projects.tools.index', $project) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow-sm transition-colors duration-200 text-sm font-medium inline-flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Reset
                                    </a>
                                    
                                    <a href="{{ route('projects.tools.create', $project) }}" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-2 rounded-md shadow-md transform hover:scale-105 transition-all duration-200 text-sm font-medium inline-flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Add Request
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if($tools->count() > 0)
                        <div class="overflow-x-auto -mx-3 sm:mx-0">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-green-50 to-blue-50">
                                    <tr>
                                        <!-- Dynamic first column header -->
                                        <th id="firstColumnHeader" class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200">
                                            <!-- Default state: show No -->
                                            <span id="numberHeader">No</span>
                                            <!-- Bulk mode: show Select with checkbox (initially hidden) -->
                                            <div id="selectHeader" class="hidden">
                                                <input type="checkbox" id="selectAllCheckbox" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                                <span class="ml-2">Select</span>
                                            </div>
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200">
                                            Description
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200">
                                            Quantity
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200">
                                            Unit
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200">
                                            GL Code
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200 hidden lg:table-cell">
                                            Delivery Date
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200">
                                            Document Info
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200 hidden lg:table-cell">
                                            Status Document
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200 hidden xl:table-cell">
                                            Status Request
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200 hidden md:table-cell">
                                            Remarks
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($tools as $loop => $tool)
                                        <tr class="hover:bg-green-50 transition-colors duration-150">
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm border-r border-gray-100">
                                                <!-- Default state: show row number -->
                                                <span class="numberCell font-medium text-gray-600">{{ $loop->iteration }}</span>
                                                <!-- Bulk mode: show checkbox (initially hidden) -->
                                                <input type="checkbox" name="selected_tools[]" value="{{ $tool->idTools }}" class="tool-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500 hidden">
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm text-gray-900 border-r border-gray-100 break-words">
                                                <div class="font-medium">{{ $tool->Description }}</div>
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r border-gray-100 text-center">
                                                {{ number_format($tool->quantity) }}
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-100 text-center">
                                                {{ $tool->unit }}
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-100 text-center">
                                                {{ $tool->kode_GL }}
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 border-r border-gray-100 hidden lg:table-cell">
                                                @if($tool->delivery_date)
                                                    {{ $tool->delivery_date->format('d/m/Y') }}
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm border-r border-gray-100">
                                                @if($tool->document)
                                                    <div class="bg-blue-100 rounded-lg p-2">
                                                        <div class="font-medium text-blue-800">{{ $tool->document->jenis_request }}</div>
                                                        <div class="text-xs text-blue-600">{{ $tool->document->no_request }}</div>
                                                        <div class="text-xs text-blue-500">{{ $tool->document->date_issue->format('d/m/Y') }}</div>
                                                        <button class="mt-2 text-xs bg-blue-200 hover:bg-blue-300 text-blue-800 px-2 py-1 rounded transition-colors change-document-btn" 
                                                                data-tool-id="{{ $tool->idTools }}"
                                                                data-current-doc="{{ $tool->document->no_request }}">
                                                            Change
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="bg-gray-100 rounded-lg p-2">
                                                        <div class="text-center mb-2">
                                                            <span class="text-gray-500 text-xs">Not assigned yet</span>
                                                        </div>
                                                        <button class="w-full text-xs bg-purple-600 hover:bg-purple-700 text-white px-2 py-1 rounded transition-colors assign-document-btn" 
                                                                data-tool-id="{{ $tool->idTools }}">
                                                            Assign Document
                                                        </button>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm border-r border-gray-100 hidden lg:table-cell">
                                                @if($tool->document)
                                                    @php
                                                        $statusName = 'Unprocessed';
                                                        $colorClass = 'gray';
                                                        
                                                        if($tool->document->tahapans->isNotEmpty()) {
                                                            $currentTahapan = $tool->document->getCurrentTahapan();
                                                            if($currentTahapan) {
                                                                $statusName = $currentTahapan->namaTahapan;
                                                                $colorClass = $currentTahapan->getTahapanColor();
                                                            }
                                                        }
                                                        // Fallback: jika masih tidak ada tahapan sama sekali, gunakan default
                                                    @endphp
                                                    <a href="{{ route('documents.show', ['document' => base64_encode($tool->document->no_request)]) }}" 
                                                       class="block bg-{{ $colorClass }}-100 hover:bg-{{ $colorClass }}-200 rounded-lg p-2 transition-colors duration-200">
                                                        <div class="text-xs font-medium text-{{ $colorClass }}-800 text-center">
                                                            {{ $statusName }}
                                                        </div>
                                                    </a>
                                                @else
                                                    <div class="bg-gray-100 rounded-lg p-2">
                                                        <div class="text-xs text-gray-500 text-center">
                                                            No Document
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            
                                            <!-- Status Request Column -->
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm border-r border-gray-100 hidden xl:table-cell">
                                                @if($tool->document)
                                                    @php
                                                        $documentProgress = $tool->document->getProgressPercentage();
                                                        $canAccess = $documentProgress >= 100;
                                                        $requestStatus = $tool->document->request ? $tool->document->request->status_req : null;
                                                        $requestColor = $tool->document->request ? $tool->document->request->getStatusColor() : 'gray';
                                                    @endphp
                                                    
                                                    @if($canAccess)
                                                        <!-- Can access - show actual status or create button -->
                                                        @if($tool->document->request)
                                                            <button onclick="showRequestModal('{{ $tool->document->no_request }}')" 
                                                                    class="w-full bg-{{ $requestColor }}-100 hover:bg-{{ $requestColor }}-200 text-{{ $requestColor }}-800 px-3 py-2 rounded-md text-xs font-medium transition-colors duration-200">
                                                                {{ $requestStatus }}
                                                            </button>
                                                        @else
                                                            <button onclick="showRequestModal('{{ $tool->document->no_request }}')" 
                                                                    class="w-full bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-2 rounded-md text-xs font-medium transition-colors duration-200">
                                                                Create Request
                                                            </button>
                                                        @endif
                                                    @else
                                                        <!-- Cannot access - show pending -->
                                                        <button onclick="showPendingModal({{ $documentProgress }})" 
                                                                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-2 rounded-md text-xs font-medium transition-colors duration-200 cursor-not-allowed">
                                                            Pending
                                                        </button>
                                                    @endif
                                                @else
                                                    <div class="bg-gray-100 rounded-lg p-2">
                                                        <div class="text-xs text-gray-500 text-center">
                                                            No Document
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm text-gray-600 border-r border-gray-100 break-words hidden md:table-cell">
                                                @if($tool->remarks)
                                                    {{ $tool->remarks }}
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-1">
                                                    <a href="{{ route('projects.tools.edit', [$project, $tool]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md inline-flex items-center text-xs transition-colors duration-200 justify-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <button type="button" 
                                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md inline-flex items-center text-xs transition-colors duration-200 w-full sm:w-auto justify-center"
                                                            onclick="openDeleteModal('{{ $tool->id }}', '{{ $tool->nama_tools }}', '{{ route('projects.tools.destroy', [$project, $tool]) }}')">
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
                    @else
                        <div class="text-center py-16">
                            <div class="mb-6">
                                <svg class="mx-auto h-20 w-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            </div>
                            <div class="max-w-md mx-auto">
                                <h3 class="text-xl font-medium text-gray-600 mb-3">
                                    @if(request('search'))
                                        No tools found
                                    @else
                                        No tools added yet
                                    @endif
                                </h3>
                                <p class="text-gray-500 mb-6">
                                    @if(request('search'))
                                        No tools found with keyword "{{ request('search') }}". Try different keywords or add a new tool.
                                    @else
                                        No tools added yet for this project. Start by adding your first tool.
                                    @endif
                                </p>
                                @if(request('search'))
                                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                        <a href="{{ route('projects.tools.index', $project) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition-all duration-200 inline-flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Clear Search
                                        </a>
                                        <a href="{{ route('projects.tools.create', $project) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition-all duration-200 inline-flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Add New Tool
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('documents.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm inline-flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Manage Documents
                        </a>
                        <a href="{{ route('bidangs.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm inline-flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Manage GL Code
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Assignment Modal -->
    <div id="bulkAssignModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-0 border-0 shadow-2xl rounded-xl bg-white max-w-md">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 rounded-t-xl">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Assign Tools ke Document
                </h3>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <div class="mb-4">
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                        <p class="text-sm text-purple-800 font-medium">
                            Choose document for <span id="selectedToolsCount" class="font-bold text-purple-900">0</span> selected tools
                        </p>
                    </div>
                </div>
                
                <form id="bulkAssignForm" action="{{ route('projects.tools.process-bulk-assign', $project) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label for="bulk_document_no" class="block text-sm font-medium text-gray-700 mb-2">
                            Document <span class="text-red-500">*</span>
                        </label>
                        <select name="no_document" id="bulk_document_no" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 bg-white" required>
                            <option value="">Choose Document...</option>
                            @foreach($documents as $doc)
                                <option value="{{ $doc->no_request }}">{{ $doc->no_request }} - {{ $doc->jenis_request }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-md text-sm font-medium flex-1 transition-colors duration-200 shadow-sm flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Assign Tools
                        </button>
                        <button type="button" id="cancelBulkAssign" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium flex-1 transition-colors duration-200 shadow-sm flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Individual Assignment Modal -->
    <div id="individualAssignModal" class="fixed inset-0 hidden z-50">
        <!-- Backdrop with blur -->
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-all duration-300 opacity-0" id="individualModalBackdrop"></div>
        
        <!-- Modal -->
        <div class="fixed inset-0 flex items-center justify-center p-4 pt-16 pb-8 overflow-y-auto transition-all duration-300 opacity-0" id="individualModalWrapper">
            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full relative transform transition-all duration-300 scale-95 overflow-hidden border border-gray-100" id="individualModalContent">
                    <!-- Modal Header -->
                    <div class="relative bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 px-6 py-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white" id="assignModalTitle">
                                        Assign Tool to Document
                                    </h3>
                                    <p class="text-blue-100 text-xs mt-0.5">Manage document assignment for tools</p>
                                </div>
                            </div>
                            <button type="button" id="closeIndividualModal" class="p-2 text-white hover:text-gray-200 hover:bg-white hover:bg-opacity-20 rounded-lg transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Decorative elements -->
                        <div class="absolute top-0 right-0 -mt-1 -mr-1 w-10 h-10 bg-white bg-opacity-10 rounded-full"></div>
                        <div class="absolute bottom-0 left-0 -mb-1 -ml-1 w-8 h-8 bg-white bg-opacity-10 rounded-full"></div>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="p-6 bg-gray-50">
                        <!-- Status/Info Card -->
                        <div class="mb-6">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg p-4 shadow-sm">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-blue-800 mb-1" id="assignModalDescription">
                                            Choose document for selected tool
                                        </p>
                                        <div class="text-xs text-blue-600" id="assignModalSubDescription">
                                            Select the appropriate document from the list below
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <form id="individualAssignForm" method="POST" class="space-y-6">
                            @csrf
                            @method('PATCH')
                            
                            <!-- Current Document Info (for change action) -->
                            <div id="currentDocumentInfo" class="hidden">
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-4 h-4 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-amber-800">Currently Assigned To:</span>
                                    </div>
                                    <p class="text-sm text-amber-700 font-semibold" id="currentDocumentName">Document Name</p>
                                </div>
                            </div>
                            
                            <!-- Document Selection -->
                            <div class="space-y-3">
                                <label for="individual_document_no" class="block text-sm font-semibold text-gray-700">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Select Document
                                        <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                <div class="relative">
                                    <select name="document_no" id="individual_document_no" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm 
                                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                   bg-white text-gray-900 text-sm transition-all duration-200
                                                   hover:border-gray-400" required>
                                        <option value="" class="text-gray-500">Choose Document...</option>
                                        @foreach($documents as $doc)
                                            <option value="{{ $doc->no_request }}" class="text-gray-900">
                                                {{ $doc->no_request }} - {{ $doc->jenis_request }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Select the document you want to assign this tool to
                                </p>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-white border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row gap-3 justify-end">
                            <button type="button" id="cancelIndividualAssign" 
                                    class="px-6 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 
                                           border border-gray-300 rounded-lg text-sm font-medium 
                                           transition-all duration-200 transform hover:scale-105
                                           flex items-center justify-center shadow-sm hover:shadow">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </button>
                            <button type="submit" form="individualAssignForm" id="confirmAssignBtn"
                                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 
                                           hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg 
                                           text-sm font-medium transition-all duration-200 transform hover:scale-105
                                           flex items-center justify-center shadow-lg hover:shadow-xl
                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span id="confirmBtnText">Confirm Assignment</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const toolCheckboxes = document.querySelectorAll('.tool-checkbox');
            const selectAllBtn = document.getElementById('selectAllBtn');
            const clearSelectionBtn = document.getElementById('clearSelectionBtn');
            const bulkAssignBtn = document.getElementById('bulkAssignBtn');
            const selectedCount = document.getElementById('selectedCount');
            const bulkAssignModal = document.getElementById('bulkAssignModal');
            const cancelBulkAssign = document.getElementById('cancelBulkAssign');
            const bulkAssignForm = document.getElementById('bulkAssignForm');
            const selectedToolsCount = document.getElementById('selectedToolsCount');
            
            // Individual assignment elements
            const assignDocumentBtns = document.querySelectorAll('.assign-document-btn');
            const changeDocumentBtns = document.querySelectorAll('.change-document-btn');
            const individualAssignModal = document.getElementById('individualAssignModal');
            const cancelIndividualAssign = document.getElementById('cancelIndividualAssign');
            const individualAssignForm = document.getElementById('individualAssignForm');
            const assignModalTitle = document.getElementById('assignModalTitle');
            const assignModalDescription = document.getElementById('assignModalDescription');
            const confirmAssignBtn = document.getElementById('confirmAssignBtn');

            // Update selected count and button states
            function updateSelectionState() {
                const selectedTools = document.querySelectorAll('.tool-checkbox:checked');
                const count = selectedTools.length;
                
                selectedCount.textContent = count + ' tools selected';
                selectedCount.classList.toggle('hidden', count === 0);
                bulkAssignBtn.disabled = count === 0;
                
                // Update select all checkbox state
                if (count === 0) {
                    selectAllCheckbox.indeterminate = false;
                    selectAllCheckbox.checked = false;
                } else if (count === toolCheckboxes.length) {
                    selectAllCheckbox.indeterminate = false;
                    selectAllCheckbox.checked = true;
                } else {
                    selectAllCheckbox.indeterminate = true;
                    selectAllCheckbox.checked = false;
                }
            }

            // Select all functionality
            selectAllBtn.addEventListener('click', function() {
                selectAllTools();
            });

            // Clear selection functionality
            clearSelectionBtn.addEventListener('click', function() {
                clearAllSelections();
            });

            // Select all checkbox functionality
            selectAllCheckbox.addEventListener('change', function() {
                toolCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
                updateSelectionState();
            });

            // Individual checkbox change
            toolCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectionState);
            });

            // Bulk assign button
            bulkAssignBtn.addEventListener('click', function() {
                const selectedTools = document.querySelectorAll('.tool-checkbox:checked');
                console.log('Selected tools:', selectedTools.length);
                selectedToolsCount.textContent = selectedTools.length;
                
                if (selectedTools.length === 0) {
                    alert('Pilih minimal satu tool terlebih dahulu');
                    return;
                }
                
                // Clear previous hidden inputs
                const existingInputs = bulkAssignForm.querySelectorAll('input[name="tool_ids[]"]');
                existingInputs.forEach(input => input.remove());
                
                // Add selected tool IDs to form
                selectedTools.forEach(checkbox => {
                    console.log('Adding tool ID:', checkbox.value);
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'tool_ids[]';
                    hiddenInput.value = checkbox.value;
                    bulkAssignForm.appendChild(hiddenInput);
                });
                
                bulkAssignModal.classList.remove('hidden');
            });

            // Cancel bulk assign
            cancelBulkAssign.addEventListener('click', function() {
                bulkAssignModal.classList.add('hidden');
            });

            // Debug bulk assign form submission
            bulkAssignForm.addEventListener('submit', function(e) {
                console.log('Form submitting...');
                const formData = new FormData(this);
                console.log('Form data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }
                
                const toolIds = formData.getAll('tool_ids[]');
                const document = formData.get('no_document');
                console.log('Tool IDs to assign:', toolIds);
                console.log('Document selected:', document);
                
                if (toolIds.length === 0) {
                    e.preventDefault();
                    alert('Error: There are no selected tools to assign');
                    return false;
                }
                
                if (!document) {
                    e.preventDefault();
                    alert('Error: Please select a document first');
                    return false;
                }
            });

            // Close modal when clicking outside
            bulkAssignModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });

            // Individual Assignment Functionality
            assignDocumentBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const toolId = this.getAttribute('data-tool-id');
                    showIndividualAssignModal(toolId, 'assign');
                });
            });

            changeDocumentBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const toolId = this.getAttribute('data-tool-id');
                    const currentDoc = this.getAttribute('data-current-doc');
                    showIndividualAssignModal(toolId, 'change', currentDoc);
                });
            });

            function showIndividualAssignModal(toolId, action, currentDoc = null) {
                const modal = document.getElementById('individualAssignModal');
                const modalBackdrop = document.getElementById('individualModalBackdrop');
                const modalWrapper = document.getElementById('individualModalWrapper');
                const modalContent = document.getElementById('individualModalContent');
                const modalTitle = document.getElementById('assignModalTitle');
                const modalDescription = document.getElementById('assignModalDescription');
                const modalSubDescription = document.getElementById('assignModalSubDescription');
                const confirmBtn = document.getElementById('confirmAssignBtn');
                const confirmBtnText = document.getElementById('confirmBtnText');
                const currentDocInfo = document.getElementById('currentDocumentInfo');
                const currentDocName = document.getElementById('currentDocumentName');

                if (action === 'assign') {
                    modalTitle.textContent = 'Assign Tool to Document';
                    modalDescription.textContent = 'Choose document for selected tool';
                    modalSubDescription.textContent = 'Select the appropriate document from the list below';
                    confirmBtnText.textContent = 'Confirm Assignment';
                    currentDocInfo.classList.add('hidden');
                    
                    // Update header gradient for assign action
                    const header = modal.querySelector('.bg-gradient-to-r');
                    header.className = 'relative bg-gradient-to-r from-green-600 via-green-700 to-emerald-700 px-6 py-3';
                } else {
                    modalTitle.textContent = 'Change Document Assignment';
                    modalDescription.textContent = 'Change document assignment for this tool';
                    modalSubDescription.textContent = 'Tool will be reassigned to the selected document';
                    confirmBtnText.textContent = 'Change Assignment';
                    currentDocName.textContent = currentDoc;
                    currentDocInfo.classList.remove('hidden');
                    
                    // Update header gradient for change action
                    const header = modal.querySelector('.bg-gradient-to-r');
                    header.className = 'relative bg-gradient-to-r from-amber-600 via-orange-600 to-red-600 px-6 py-3';
                }

                // Set form action URL
                const formAction = `/projects/{{ $project->no_IO }}/tools/${toolId}/quick-assign`;
                individualAssignForm.setAttribute('action', formAction);
                
                // Show modal with entrance animation
                modal.classList.remove('hidden');
                
                // Trigger reflow to ensure the element is rendered
                modal.offsetHeight;
                
                setTimeout(() => {
                    modalBackdrop.classList.remove('opacity-0');
                    modalWrapper.classList.remove('opacity-0');
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }, 10);
            }

            // Close modal function with animation
            function closeIndividualModal() {
                const modal = document.getElementById('individualAssignModal');
                const modalBackdrop = document.getElementById('individualModalBackdrop');
                const modalWrapper = document.getElementById('individualModalWrapper');
                const modalContent = document.getElementById('individualModalContent');
                
                modalBackdrop.classList.add('opacity-0');
                modalWrapper.classList.add('opacity-0');
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');
                
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            // Cancel individual assign
            cancelIndividualAssign.addEventListener('click', closeIndividualModal);
            document.getElementById('closeIndividualModal').addEventListener('click', closeIndividualModal);

            // Close individual modal when clicking outside
            const individualModalBackdrop = document.getElementById('individualModalBackdrop');
            individualModalBackdrop.addEventListener('click', closeIndividualModal);
            
            document.getElementById('individualModalWrapper').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeIndividualModal();
                }
            });

            // Handle form submission with loading state
            individualAssignForm.addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('confirmAssignBtn');
                const submitBtnText = document.getElementById('confirmBtnText');
                const selectElement = document.getElementById('individual_document_no');
                
                // Validate form
                if (!selectElement.value) {
                    e.preventDefault();
                    
                    // Show error state
                    selectElement.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                    selectElement.focus();
                    
                    // Create error message if it doesn't exist
                    let errorMsg = selectElement.parentNode.querySelector('.error-message');
                    if (!errorMsg) {
                        errorMsg = document.createElement('p');
                        errorMsg.className = 'error-message text-red-500 text-xs mt-1 flex items-center';
                        errorMsg.innerHTML = `
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Please select a document
                        `;
                        selectElement.parentNode.appendChild(errorMsg);
                    }
                    
                    // Remove error state after user selects something
                    selectElement.addEventListener('change', function() {
                        this.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                        const errorMsg = this.parentNode.querySelector('.error-message');
                        if (errorMsg) {
                            errorMsg.remove();
                        }
                    }, { once: true });
                    
                    return false;
                }
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.classList.add('btn-loading');
                submitBtnText.textContent = 'Processing...';
                
                // Optional: Add a timeout to reset if something goes wrong
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('btn-loading');
                        submitBtnText.textContent = 'Confirm Assignment';
                    }
                }, 10000); // 10 second timeout
            });

            // Keyboard navigation for individual modal
            document.addEventListener('keydown', function(e) {
                const modal = document.getElementById('individualAssignModal');
                if (!modal.classList.contains('hidden')) {
                    if (e.key === 'Escape') {
                        e.preventDefault();
                        closeIndividualModal();
                    }
                    
                    // Trap focus within modal
                    if (e.key === 'Tab') {
                        const focusableElements = modal.querySelectorAll(
                            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
                        );
                        const first = focusableElements[0];
                        const last = focusableElements[focusableElements.length - 1];
                        
                        if (e.shiftKey) {
                            if (document.activeElement === first) {
                                e.preventDefault();
                                last.focus();
                            }
                        } else {
                            if (document.activeElement === last) {
                                e.preventDefault();
                                first.focus();
                            }
                        }
                    }
                }
            });

            // Initialize
            updateSelectionState();
        });

        // Global functions for mode switching and selection management
        function selectAllTools() {
            document.querySelectorAll('.tool-checkbox').forEach(checkbox => checkbox.checked = true);
            document.getElementById('selectAllCheckbox').checked = true;
            updateSelectionState();
        }

        function clearAllSelections() {
            document.querySelectorAll('.tool-checkbox').forEach(checkbox => checkbox.checked = false);
            document.getElementById('selectAllCheckbox').checked = false;
            updateSelectionState();
        }

        function updateSelectionState() {
            const selectedTools = document.querySelectorAll('.tool-checkbox:checked');
            const count = selectedTools.length;
            const totalTools = document.querySelectorAll('.tool-checkbox').length;
            
            document.getElementById('selectedCount').textContent = count + ' tools selected';
            document.getElementById('bulkAssignBtn').disabled = count === 0;
            
            // Update select all checkbox state
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            if (count === 0) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = false;
            } else if (count === totalTools) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = true;
            } else {
                selectAllCheckbox.indeterminate = true;
                selectAllCheckbox.checked = false;
            }
        }

        // Mode switching functions
        function enterBulkMode() {
            // Hide initial assign button and show bulk assignment section
            document.getElementById('initialAssignSection').classList.add('hidden');
            document.getElementById('bulkAssignSection').classList.remove('hidden');
            
            // Switch table headers
            document.getElementById('numberHeader').classList.add('hidden');
            document.getElementById('selectHeader').classList.remove('hidden');
            
            // Switch table cells: hide numbers, show checkboxes
            document.querySelectorAll('.numberCell').forEach(cell => cell.classList.add('hidden'));
            document.querySelectorAll('.tool-checkbox').forEach(checkbox => checkbox.classList.remove('hidden'));
            
            // Clear any previous selections
            clearAllSelections();
        }

        function exitBulkMode() {
            // Show initial assign button and hide bulk assignment section  
            document.getElementById('initialAssignSection').classList.remove('hidden');
            document.getElementById('bulkAssignSection').classList.add('hidden');
            
            // Switch table headers
            document.getElementById('numberHeader').classList.remove('hidden');
            document.getElementById('selectHeader').classList.add('hidden');
            
            // Switch table cells: show numbers, hide checkboxes
            document.querySelectorAll('.numberCell').forEach(cell => cell.classList.remove('hidden'));
            document.querySelectorAll('.tool-checkbox').forEach(checkbox => {
                checkbox.classList.add('hidden');
                checkbox.checked = false; // Clear selections
            });
            
            // Clear select all checkbox
            document.getElementById('selectAllCheckbox').checked = false;
        }
    </script>

    <!-- Request Modal -->
    <div id="requestModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden opacity-0 z-50 transition-opacity duration-300">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-xl shadow-xl rounded-lg bg-white transform transition-all duration-300 scale-95" id="modalContent">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="requestModalTitle">Request Details</h3>
                    <button onclick="closeRequestModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div id="requestModalContent">
                    <div class="text-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="text-gray-500 mt-2">Loading...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Modal -->
    <div id="pendingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Document Stage Not Yet Completed</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="pendingMessage">
                        Document stage must reach 100% (EPC TO PROCUREMENT) before accessing the request.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button onclick="closePendingModal()" class="px-4 py-2 bg-yellow-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                        OK
                    </button>
                </div>
            </div>
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
                            Confirm Delete Tools
                        </h3>
                        <button type="button" onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="mb-6">
                        <p class="text-gray-600 mb-3">Are you sure you want to delete this tool?</p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-800 font-semibold" id="toolName">Tool Name</p>
                            <p class="text-red-600 text-sm mt-1">
                                ⚠️ Deleted data cannot be recovered
                            </p>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeDeleteModal()"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 border border-gray-300 rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                        <form id="deleteForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                                Yes, Delete Tool
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Request Modal Functions
        function showRequestModal(documentNo) {
            const modal = document.getElementById('requestModal');
            const content = document.getElementById('requestModalContent');
            
            // Show modal with loading state and entrance animation
            modal.classList.remove('hidden');
            const modalContent = document.getElementById('modalContent');
            
            // Trigger entrance animation
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
            }, 10);
            
            content.innerHTML = `
                <div class="text-center py-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="text-gray-500 mt-2">Loading...</p>
                </div>
            `;

            // Fetch request data
            fetch(`/requests/document/${encodeURIComponent(documentNo)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        // Show enhanced error modal for BOQ-related issues
                        if (data.action && (data.action === 'create_boq' || data.action === 'manage_boq')) {
                            content.innerHTML = `
                                <div class="text-center py-6">
                                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                                        <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">${data.error}</h3>
                                    <p class="text-gray-600 mb-6 leading-relaxed">${data.message}</p>
                                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                        ${data.action === 'create_boq' ? `
                                            <a href="/projects/${data.project_id}/boq/create" 
                                               class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium inline-flex items-center justify-center transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Buat BOQ
                                            </a>
                                        ` : `
                                            <a href="/projects/${data.project_id}/boq" 
                                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium inline-flex items-center justify-center transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Kelola BOQ
                                            </a>
                                        `}
                                        <button type="button" onclick="closeRequestModal()" 
                                                class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md font-medium transition-colors">
                                            Nanti Saja
                                        </button>
                                    </div>
                                </div>
                            `;
                        } else {
                            // Show regular error with better styling
                            content.innerHTML = `
                                <div class="text-center py-6">
                                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Terjadi Kesalahan</h3>
                                    <p class="text-red-600 mb-4">${data.message}</p>
                                    <button type="button" onclick="closeRequestModal()" 
                                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md font-medium transition-colors">
                                        Tutup
                                    </button>
                                </div>
                            `;
                        }
                        return;
                    }

                    // Show request form
                    showRequestForm(data.request, documentNo);
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML = `
                        <div class="text-center py-4">
                            <p class="text-red-600">Error loading request data</p>
                        </div>
                    `;
                });
        }

        function showRequestForm(request, documentNo) {
            const content = document.getElementById('requestModalContent');
            
            content.innerHTML = `
                <form id="requestForm" onsubmit="updateRequest(event, ${request ? request.id_req : 'null'})">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type Surat</label>
                            <select name="type_surat" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="SPS" ${request && request.type_surat === 'SPS' ? 'selected' : ''}>SPS</option>
                                <option value="SPMP" ${request && request.type_surat === 'SPMP' ? 'selected' : ''}>SPMP</option>
                                <option value="PCM" ${request && request.type_surat === 'PCM' ? 'selected' : ''}>PCM</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Request</label>
                            <select name="jenis_req" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="PO" ${request && request.jenis_req === 'PO' ? 'selected' : ''}>PO</option>
                                <option value="Kontrak" ${request && request.jenis_req === 'Kontrak' ? 'selected' : ''}>Kontrak</option>
                                <option value="PCM" ${request && request.jenis_req === 'PCM' ? 'selected' : ''}>PCM</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No Surat</label>
                            <input type="text" name="no_surat" value="${request ? request.no_surat : ''}" required maxlength="45" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date Request</label>
                            <input type="date" name="date_req" value="${request ? request.date_req : new Date().toISOString().split('T')[0]}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status Request</label>
                            <select name="status_req" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="On Proses" ${!request || request.status_req === 'On Proses' ? 'selected' : ''}>On Proses</option>
                                <option value="Closed" ${request && request.status_req === 'Closed' ? 'selected' : ''}>Closed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeRequestModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            ${request ? 'Update' : 'Create'} Request
                        </button>
                    </div>
                </form>
            `;
        }

        function updateRequest(event, requestId) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            
            const url = requestId ? `/requests/${requestId}` : '/requests';
            const method = requestId ? 'PUT' : 'POST';
            
            // Convert FormData to regular object for fetch
            const data = Object.fromEntries(formData.entries());
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error: ' + data.message);
                } else {
                    alert(data.success || 'Request updated successfully!');
                    closeRequestModal();
                    location.reload(); // Refresh to show updated status
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating request');
            });
        }

        function closeRequestModal() {
            const modal = document.getElementById('requestModal');
            const modalContent = document.getElementById('modalContent');
            
            // Add exit animation
            modal.classList.add('opacity-0');
            modalContent.classList.add('scale-95');
            
            // Hide modal after animation
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
            }, 200);
        }

        // Pending Modal Functions
        function showPendingModal(progress) {
            const modal = document.getElementById('pendingModal');
            const message = document.getElementById('pendingMessage');
            
            message.textContent = `Tahapan document saat ini ${progress}%. Harus mencapai 100% (EPC TO PROCUREMENT) sebelum dapat mengakses request.`;
            modal.classList.remove('hidden');
        }

        function closePendingModal() {
            document.getElementById('pendingModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.getElementById('requestModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRequestModal();
            }
        });

        document.getElementById('pendingModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePendingModal();
            }
        });

        // Delete Modal Functions
        function openDeleteModal(toolId, toolName, deleteUrl) {
            console.log('openDeleteModal called with:', toolId, toolName, deleteUrl);
            
            const modal = document.getElementById('deleteModal');
            const toolNameElement = document.getElementById('toolName');
            const deleteForm = document.getElementById('deleteForm');
            
            // Set tool name
            toolNameElement.textContent = toolName;
            
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
        });
    </script>

    <!-- Custom Styles for Individual Assignment Modal -->
    <style>
        /* Enhanced modal animations */
        #individualModalBackdrop {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }
        
        #individualModalContent {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Custom select styling */
        #individual_document_no {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
        
        #individual_document_no:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Button hover effects */
        .hover\:scale-105:hover {
            transform: scale(1.05);
        }
        
        /* Glassmorphism effect for status cards */
        .bg-gradient-to-r.from-blue-50.to-indigo-50 {
            background: linear-gradient(135deg, rgba(239, 246, 255, 0.8) 0%, rgba(238, 242, 255, 0.8) 100%);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .bg-amber-50 {
            background: linear-gradient(135deg, rgba(255, 251, 235, 0.9) 0%, rgba(254, 243, 199, 0.9) 100%);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        /* Enhanced select dropdown */
        #individual_document_no option {
            padding: 0.75rem;
            color: #374151;
        }
        
        #individual_document_no option:hover {
            background-color: #f3f4f6;
        }
        
        /* Modal entrance animation */
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        .modal-enter {
            animation: modalFadeIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Enhanced button gradients */
        .bg-gradient-to-r.from-blue-600.to-indigo-600:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #4338ca 100%);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }
        
        /* Loading state for buttons */
        .btn-loading {
            position: relative;
            color: transparent;
        }
        
        .btn-loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Improved focus states */
        button:focus-visible,
        select:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }
        
        /* Responsive improvements */
        @media (max-width: 640px) {
            #individualModalContent {
                margin: 1rem;
                max-width: calc(100vw - 2rem);
            }
            
            #individualModalWrapper {
                padding-top: 6rem; /* Extra space from navbar on mobile */
            }
        }
        
        /* Ensure modal has proper spacing from top */
        #individualModalWrapper {
            padding-top: 4rem; /* Space for navbar */
        }
    </style>
</x-app-layout>