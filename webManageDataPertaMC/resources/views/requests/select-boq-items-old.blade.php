<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    Select BOQ Items for Tool Request
                </h2>
                <p class="text-sm text-gray-600 mt-1">Document: {{ $document->no_request }}</p>
                <p class="text-sm text-gray-600">Project: {{ $project->nama_project }} ({{ $project->no_IO }})</p>
                <p class="text-sm text-gray-600">BOQ: {{ $boq->nomorBoq }}</p>
            </div>
            <div class="mt-3 sm:mt-0">
                <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">

                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Selection Summary -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <span class="text-green-800 font-medium">Items choosen: <span id="selectedCount">0</span></span>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" id="selectAllBtn" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    Select All Item Available
                                </button>
                                <span class="text-gray-400">|</span>
                                <button type="button" id="deselectAllBtn" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Delete All Selection
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- BOQ Items Selection Form -->
                    <form action="{{ route('requests.process-boq-selection', $document->no_request) }}" method="POST" id="boqSelectionForm">
                        @csrf
                    @if($boq->sections->count() > 0)
                        @foreach($boq->sections as $section)
                            <div class="mb-8 border border-gray-200 rounded-lg">
                                <!-- Section Header -->
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-base font-semibold text-gray-900">
                                            {{ $section->nama_section }}
                                        </h3>
                                        <div class="text-sm text-gray-500">
                                            {{ $section->details->count() }} item{{ $section->details->count() !== 1 ? 's' : '' }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Section Details -->
                                <div class="p-4">
                                    @if($section->details->count() > 0)
                                        <div class="space-y-3">
                                            @foreach($section->details as $detail)
                                                <label class="flex items-start space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                                    <input type="checkbox" 
                                                           name="selected_details[]" 
                                                           value="{{ $detail->no }}"
                                                           class="detail-checkbox mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-start justify-between">
                                                            <div class="flex-1">
                                                                <p class="text-sm font-medium text-gray-900">
                                                                    {{ $detail->nama_detail }}
                                                                </p>
                                                                <div class="mt-1 text-xs text-gray-500 space-y-1">
                                                                    <div class="flex items-center space-x-4">
                                                                        <span>Available: <span class="font-medium">{{ number_format($detail->getAvailableQuantity()) }}</span></span>
                                                                        <span>Unit: <span class="font-medium">{{ $detail->unit }}</span></span>
                                                                        <span>Harga: <span class="font-medium">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</span></span>
                                                                    </div>
                                                                    <div>
                                                                        Total Original: <span class="font-medium text-gray-700">Rp {{ number_format($detail->quantity * $detail->harga_satuan, 0, ',', '.') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 text-sm italic">Tidak ada detail dalam section ini.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada section</h3>
                            <p class="mt-1 text-sm text-gray-500">BOQ ini belum memiliki section atau detail.</p>
                        </div>
                    @endif
                </div>

                <!-- Form Actions -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-lg">
                    <div class="flex items-center justify-between">
                        <div id="selectedSummary" class="text-sm text-gray-600">
                            Belum ada item yang dipilih
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit" id="continueBtn" disabled class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                Lanjutkan ke Form Request
                                <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.detail-checkbox');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const deselectAllBtn = document.getElementById('deselectAllBtn');
    const selectedCount = document.getElementById('selectedCount');
    const selectedSummary = document.getElementById('selectedSummary');
    const continueBtn = document.getElementById('continueBtn');

    function updateSelection() {
        const checkedBoxes = document.querySelectorAll('.detail-checkbox:checked');
        const count = checkedBoxes.length;
        
        selectedCount.textContent = `${count} item${count !== 1 ? 's' : ''} dipilih`;
        
        if (count === 0) {
            selectedSummary.textContent = 'Belum ada item yang dipilih';
            continueBtn.disabled = true;
        } else {
            selectedSummary.textContent = `${count} item BOQ dipilih untuk di-request`;
            continueBtn.disabled = false;
        }
    }

    // Event listeners for checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelection);
    });

    // Select all functionality
    selectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSelection();
    });

    // Deselect all functionality
    deselectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelection();
    });

    // Initial update
    updateSelection();
});
</script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>