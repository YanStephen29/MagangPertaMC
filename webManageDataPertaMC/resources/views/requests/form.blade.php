@extends('layouts.app')

@section('title', 'Form Request - ' . $document->no_request)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Form Request</h1>
                    <p class="text-gray-600 mt-1">Document: <span class="font-semibold">{{ $document->no_request }}</span></p>
                    @if($document->tools->first() && $document->tools->first()->project)
                        <p class="text-gray-600">Project: <span class="font-semibold">{{ $document->tools->first()->project->nama_project }}</span></p>
                    @endif
                </div>
                <div>
                    <a href="{{ route('requests.select-boq-items', $document->no_request) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Reselect BOQ Items
                    </a>
                </div>
            </div>
        </div>

        @if($selectedBoqItems && $selectedBoqItems->count() > 0)
        <!-- Request Details Form for Selected BOQ Items -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Detail Request Item BOQ</h2>
                <p class="text-gray-600 text-sm mt-1">Complete information for {{ $selectedBoqItems->count() }} items to be requested</p>
            </div>
            
            <div class="p-6">
                <div class="space-y-6" id="request-details-container">
                    @foreach($selectedBoqItems as $index => $item)
                        <div class="border border-gray-200 rounded-lg p-4 request-detail-item" data-detail-id="{{ $item->id }}">
                            <!-- Item Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $item->nama_detail }}</h3>
                                    <p class="text-xs text-gray-500 mt-1">Section: {{ $item->section->nama_section }}</p>
                                    <p class="text-xs text-blue-600 mt-1">BOQ Unit: {{ $item->unit }} | Price/Unit: Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-xs text-gray-500">
                                    Item {{ $index + 1 }} from {{ $selectedBoqItems->count() }}
                                </div>
                            </div>
                            
                            <!-- Input Fields for this item -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Description/Notes -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Description/Notes <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="request_details[{{ $item->id }}][description]" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                              rows="3"
                                              placeholder="Explain the details, specifications, or special notes for this item..."
                                              required>{{ old('request_details.' . $item->id . '.description') }}</textarea>
                                </div>
                                
                                <!-- Quantity -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Quantity <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           name="request_details[{{ $item->id }}][quantity]"
                                           min="1"
                                           step="0.01"
                                           value="{{ old('request_details.' . $item->id . '.quantity', 1) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                           required
                                           onchange="updateItemTotal({{ $item->id }})">
                                    <p class="text-xs text-gray-500 mt-1">Unit: {{ $item->unit }}</p>
                                </div>
                            </div>
                            
                            <!-- Unit and Price Info -->
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Unit (Read-only, from BOQ) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit (from BOQ)</label>
                                    <input type="text" 
                                           name="request_details[{{ $item->id }}][unit]"
                                           value="{{ $item->unit }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50"
                                           readonly>
                                </div>
                                
                                <!-- Unit Price (from BOQ, read-only) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Price/Unit (from BOQ)</label>
                                    <input type="text" 
                                           value="Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50"
                                           readonly>
                                    <input type="hidden" 
                                           name="request_details[{{ $item->id }}][unit_price]"
                                           value="{{ $item->harga_satuan ?? 0 }}">
                                </div>
                                
                                <!-- Total Price (calculated) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Total</label>
                                    <input type="text" 
                                           id="total_price_{{ $item->id }}"
                                           value="Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-blue-50 font-medium text-blue-900"
                                           readonly>
                                </div>
                            </div>
                            
                            <!-- Hidden fields -->
                            <input type="hidden" name="request_details[{{ $item->id }}][detail_id]" value="{{ $item->no }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Request Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Information Request</h2>
            </div>
            
            <div class="p-6">
                <form action="{{ route('requests.update', $document->request->id_req ?? 0) }}" method="POST" id="requestForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Type Surat -->
                        <div>
                            <label for="type_surat" class="block text-sm font-medium text-gray-700 mb-2">Type Surat</label>
                            <select name="type_surat" id="type_surat" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Choose Type Surat</option>
                                @foreach(['SPS', 'SPMP', 'PCM'] as $type)
                                    <option value="{{ $type }}" {{ ($document->request->type_surat ?? '') === $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jenis Request -->
                        <div>
                            <label for="jenis_req" class="block text-sm font-medium text-gray-700 mb-2">Type Request</label>
                            <select name="jenis_req" id="jenis_req" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Choose Type Request</option>
                                @foreach(['PO', 'Kontrak', 'PCM'] as $jenis)
                                    <option value="{{ $jenis }}" {{ ($document->request->jenis_req ?? '') === $jenis ? 'selected' : '' }}>
                                        {{ $jenis }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- No Surat -->
                        <div>
                            <label for="no_surat" class="block text-sm font-medium text-gray-700 mb-2">No Surat</label>
                            <input type="text" name="no_surat" id="no_surat" 
                                   value="{{ $document->request->no_surat ?? '' }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Date Request -->
                        <div>
                            <label for="date_req" class="block text-sm font-medium text-gray-700 mb-2">Date Request</label>
                            <input type="date" name="date_req" id="date_req" required
                                   value="{{ $document->request ? $document->request->date_req->format('Y-m-d') : date('Y-m-d') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Status Request -->
                        <div>
                            <label for="status_req" class="block text-sm font-medium text-gray-700 mb-2">Status Request</label>
                            <select name="status_req" id="status_req" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @foreach(['approved' => '✅ Approved', 'pending' => '⏳ Pending', 'hold' => '🔒 Hold', 'rejected' => '❌ Rejected', 'On Proses' => '🔄 On Proses (Legacy)', 'Closed' => '✅ Closed (Legacy)'] as $value => $label)
                                    <option value="{{ $value }}" {{ ($document->request->status_req ?? 'approved') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Hidden field for selected BOQ items -->
                    @if($selectedBoqItems && $selectedBoqItems->count() > 0)
                        <input type="hidden" name="selected_boq_items" value="{{ $selectedBoqItems->pluck('id')->toJson() }}">
                        <input type="hidden" name="has_request_details" value="1">
                    @endif

                    <!-- Form Actions -->
                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('requests.select-boq-items', $document->no_request) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Item Selection
                        </a>
                        <div class="flex space-x-3">
                            <button type="button" onclick="window.history.back()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </button>
                            @if($document->request && $document->request->requestDetails && $document->request->requestDetails->count() > 0)
                                <a href="{{ route('requests.details', $document->request->id_req) }}" class="inline-flex items-center px-4 py-2 border border-green-300 rounded-md shadow-sm text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    See Detail Request
                                </a>
                            @endif
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Request
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Function to update total price for each item
function updateItemTotal(itemId) {
    const quantityInput = document.querySelector(`input[name="request_details[${itemId}][quantity]"]`);
    const unitPriceInput = document.querySelector(`input[name="request_details[${itemId}][unit_price]"]`);
    const totalPriceDisplay = document.getElementById(`total_price_${itemId}`);
    
    if (quantityInput && unitPriceInput && totalPriceDisplay) {
        const quantity = parseFloat(quantityInput.value) || 0;
        const unitPrice = parseFloat(unitPriceInput.value) || 0;
        const total = quantity * unitPrice;
        
        totalPriceDisplay.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }
}

// Initialize all totals on page load
document.addEventListener('DOMContentLoaded', function() {
    // Find all request detail items and initialize their totals
    const detailItems = document.querySelectorAll('.request-detail-item');
    detailItems.forEach(item => {
        const itemId = item.getAttribute('data-detail-id');
        if (itemId) {
            updateItemTotal(itemId);
        }
    });
    
    // Add real-time validation for required fields
    const requiredTextareas = document.querySelectorAll('textarea[required]');
    requiredTextareas.forEach(textarea => {
        textarea.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });
    });
    
    // Form validation before submit
    const form = document.getElementById('requestForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            let hasErrors = false;
            
            // Check all required textareas
            const requiredFields = this.querySelectorAll('textarea[required], input[required]:not([type="hidden"])');
            requiredFields.forEach(field => {
                if (field.value.trim() === '') {
                    field.classList.add('border-red-500');
                    hasErrors = true;
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (hasErrors) {
                e.preventDefault();
                showToast('Please complete all required fields (marked with *)', 'warning');
                
                // Scroll to first error
                const firstError = this.querySelector('.border-red-500');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });
    }
});
</script>
@endsection