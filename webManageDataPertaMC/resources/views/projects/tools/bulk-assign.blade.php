<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    Bulk Assign Tools ke Document
                </h2>
                <p class="text-sm text-gray-600 mt-1">Project: {{ $project->title_project }}</p>
            </div>
            <div class="mt-3 sm:mt-0">
                <a href="{{ route('projects.tools.index', $project) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">
                    ← Kembali ke Tools
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">


                    <div class="bg-gradient-to-r from-purple-50 to-blue-50 p-6 rounded-lg border border-purple-200 mb-6">
                        <h3 class="text-lg font-semibold text-purple-800 mb-4">📎 Assign Multiple Tools ke Document</h3>
                        
                        <form action="{{ route('projects.tools.process-bulk-assign', $project) }}" method="POST">
                            @csrf
                            
                            <!-- Document Selection -->
                            <div class="mb-6">
                                <label for="document_no" class="block text-sm font-medium text-gray-700 mb-2">
                                    📄 Pilih Document
                                </label>
                                <select name="document_no" id="document_no" class="w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
                                    <option value="">Pilih Document...</option>
                                    @foreach($documents as $doc)
                                        <option value="{{ $doc->no_request }}">
                                            {{ $doc->no_request }} - {{ $doc->jenis_request }} ({{ $doc->date_issue->format('d/m/Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Document yang akan di-assign ke tools yang dipilih</p>
                            </div>

                            <!-- Tools Selection -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-4">
                                    <label class="block text-sm font-medium text-gray-700">
                                        🔧 Pilih Tools yang akan di-assign
                                    </label>
                                    <div class="flex gap-2">
                                        <button type="button" id="selectAllTools" class="text-sm bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded-md transition-colors">
                                            Select All
                                        </button>
                                        <button type="button" id="clearAllTools" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded-md transition-colors">
                                            Clear All
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="bg-white border border-gray-200 rounded-lg max-h-96 overflow-y-auto">
                                    @if($tools->count() > 0)
                                        @foreach($tools as $tool)
                                            <div class="flex items-start p-4 border-b border-gray-100 hover:bg-gray-50">
                                                <input type="checkbox" 
                                                       name="tool_ids[]" 
                                                       value="{{ $tool->id }}" 
                                                       id="tool_{{ $tool->id }}"
                                                       class="tool-checkbox mt-1 rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                                                       {{ !$tool->document_no ? '' : 'disabled' }}>
                                                
                                                <label for="tool_{{ $tool->id }}" class="ml-3 flex-1 cursor-pointer {{ $tool->document_no ? 'opacity-50' : '' }}">
                                                    <div class="font-medium text-sm text-gray-900">{{ $tool->Description }}</div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        Qty: {{ number_format($tool->quantity) }} {{ $tool->unit }}
                                                        @if($tool->delivery_date)
                                                            | Delivery: {{ $tool->delivery_date->format('d/m/Y') }}
                                                        @endif
                                                    </div>
                                                    @if($tool->remarks)
                                                        <div class="text-xs text-gray-400 mt-1">{{ $tool->remarks }}</div>
                                                    @endif
                                                    @if($tool->document_no)
                                                        <div class="text-xs text-blue-600 mt-2 bg-blue-100 px-2 py-1 rounded inline-block">
                                                            ✓ Sudah di-assign ke: {{ $tool->document->no_request ?? $tool->document_no }}
                                                        </div>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="p-8 text-center text-gray-500">
                                            <p>Tidak ada tools yang tersedia untuk di-assign</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="mt-2 text-sm text-gray-600">
                                    <span id="selectedCount">0</span> tools dipilih
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button type="submit" class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-3 rounded-md font-medium shadow-md transform hover:scale-105 transition-all duration-200 flex-1">
                                    📎 Assign Selected Tools
                                </button>
                                <a href="{{ route('projects.tools.index', $project) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-md font-medium text-center transition-colors flex-1">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-700 mb-3">Quick Actions</h4>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('documents.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm inline-flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Buat Document Baru
                            </a>
                            <a href="{{ route('documents.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm inline-flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Lihat Semua Documents
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toolCheckboxes = document.querySelectorAll('.tool-checkbox:not([disabled])');
            const selectAllBtn = document.getElementById('selectAllTools');
            const clearAllBtn = document.getElementById('clearAllTools');
            const selectedCount = document.getElementById('selectedCount');

            function updateSelectedCount() {
                const checkedBoxes = document.querySelectorAll('.tool-checkbox:checked');
                selectedCount.textContent = checkedBoxes.length;
            }

            selectAllBtn.addEventListener('click', function() {
                toolCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                updateSelectedCount();
            });

            clearAllBtn.addEventListener('click', function() {
                toolCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateSelectedCount();
            });

            toolCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            // Initialize count
            updateSelectedCount();
        });
    </script>
</x-app-layout>