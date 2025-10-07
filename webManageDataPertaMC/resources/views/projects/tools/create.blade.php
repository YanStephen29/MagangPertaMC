<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    Tambah Request Baru
                </h2>
                <p class="text-sm text-gray-600 mt-1">Project: {{ $project->title_project }} ({{ $project->no_IO }})</p>
            </div>
            <div class="mt-3 sm:mt-0">
                <a href="{{ route('projects.tools.index', $project) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Tools
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('boq_validation_error'))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-red-800">⚠️ Validasi BOQ Gagal</h3>
                            </div>
                            
                            @php $messages = session('validation_messages', []); @endphp
                            
                            @if(isset($messages['errors']))
                                <div class="mb-4">
                                    <h4 class="font-medium text-red-800 mb-2">❌ Error:</h4>
                                    <ul class="list-disc list-inside text-red-700 space-y-1">
                                        @foreach($messages['errors'] as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(isset($messages['warnings']))
                                <div class="mb-4">
                                    <h4 class="font-medium text-yellow-800 mb-2">⚠️ Peringatan:</h4>
                                    <ul class="list-disc list-inside text-yellow-700 space-y-1">
                                        @foreach($messages['warnings'] as $warning)
                                            <li>{{ $warning }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(isset($messages['suggestions']))
                                <div class="mb-4">
                                    <h4 class="font-medium text-blue-800 mb-2">💡 Saran Item BOQ:</h4>
                                    <div class="bg-blue-50 border border-blue-200 rounded p-3">
                                        @php 
                                            $validationResult = session('validation_result', []);
                                            $suggestions = $validationResult['suggestions'] ?? [];
                                        @endphp
                                        @if(!empty($suggestions))
                                            <div class="space-y-2">
                                                @foreach($suggestions as $suggestion)
                                                    <div class="flex justify-between items-center p-2 bg-white rounded border">
                                                        <div>
                                                            <span class="font-medium text-blue-900">{{ $suggestion['detail']->nama_detail }}</span>
                                                            <span class="text-sm text-blue-700">
                                                                ({{ $suggestion['detail']->quantity }} {{ $suggestion['detail']->unit }})
                                                            </span>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="text-sm font-medium text-green-600">
                                                                Rp {{ number_format($suggestion['detail']->harga_satuan, 0, ',', '.') }}
                                                            </div>
                                                            <div class="text-xs text-blue-600">
                                                                {{ number_format($suggestion['similarity'] * 100, 1) }}% cocok
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if(isset($messages['info']))
                                <div class="mb-4">
                                    <h4 class="font-medium text-blue-800 mb-2">ℹ️ Informasi BOQ:</h4>
                                    <ul class="list-disc list-inside text-blue-700 space-y-1">
                                        @foreach($messages['info'] as $info)
                                            <li>{{ $info }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif



                            <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-4 border-t border-red-200">
                                <button type="button" onclick="fixToolData()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                    🔧 Perbaiki Data Tool
                                </button>
                                
                                <form method="POST" action="{{ route('projects.tools.store', $project) }}" class="inline">
                                    @csrf
                                    @foreach(session('tool_data', []) as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <input type="hidden" name="force_create" value="1">
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                        ⚡ Paksa Buat Tool (Abaikan BOQ)
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('projects.tools.store', $project) }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Description -->
                        <div>
                            <label for="Description" class="block text-sm font-medium text-gray-700 mb-2">
                                🔧 Deskripsi Tool <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="Description" 
                                   id="Description" 
                                   value="{{ old('Description') }}"
                                   placeholder="Contoh: Hydraulic Pump, Steel Pipe, Safety Valve, dll"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('Description') border-red-500 @enderror"
                                   maxlength="45"
                                   required>
                            @error('Description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    📊 Quantity <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity" 
                                       value="{{ old('quantity', 1) }}"
                                       min="1"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('quantity') border-red-500 @enderror"
                                       required>
                                @error('quantity')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unit -->
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                                    📏 Unit <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="unit" 
                                       id="unit" 
                                       value="{{ old('unit') }}"
                                       placeholder="pcs, meter, liter, kg, dll"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('unit') border-red-500 @enderror"
                                       maxlength="20"
                                       required>
                                @error('unit')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Delivery Date -->
                        <div>
                            <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">
                                🚚 Tanggal Delivery
                            </label>
                            <input type="date" 
                                   name="delivery_date" 
                                   id="delivery_date" 
                                   value="{{ old('delivery_date') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('delivery_date') border-red-500 @enderror">
                            @error('delivery_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-600 mt-1">Optional - kosongkan jika belum ditentukan</p>
                        </div>

                        <!-- Bidang -->
                        <div>
                            <label for="kode_GL" class="block text-sm font-medium text-gray-700 mb-2">
                                🏢 Bidang <span class="text-red-500">*</span>
                            </label>
                            <select name="kode_GL" 
                                    id="kode_GL" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('kode_GL') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Bidang...</option>
                                @foreach($bidangs as $bidang)
                                    <option value="{{ $bidang->kode_GL }}" {{ old('kode_GL') == $bidang->kode_GL ? 'selected' : '' }}>
                                        {{ $bidang->kode_GL }} - {{ $bidang->nama_Bidang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_GL')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Document Assignment -->
                        <div>
                            <label for="no_document" class="block text-sm font-medium text-gray-700 mb-2">
                                📄 Assign ke Document
                            </label>
                            <select name="no_document" 
                                    id="no_document" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('no_document') border-red-500 @enderror">
                                <option value="">Pilih Document (Optional)...</option>
                                @foreach($documents as $doc)
                                    <option value="{{ $doc->no_request }}" {{ old('no_document') == $doc->no_request ? 'selected' : '' }}>
                                        {{ $doc->no_request }} - {{ $doc->jenis_request }} ({{ $doc->date_issue->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('no_document')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-600 mt-1">Optional - tool dapat di-assign ke document nanti</p>
                        </div>

                        <!-- Remarks -->
                        <div>
                            <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">
                                💭 Remarks/Catatan
                            </label>
                            <textarea name="remarks" 
                                      id="remarks" 
                                      rows="3"
                                      placeholder="Catatan tambahan, spesifikasi khusus, dll..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('remarks') border-red-500 @enderror"
                                      maxlength="45">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-600 mt-1">Maksimal 45 karakter</p>
                        </div>

                        <!-- Information Box -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-green-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Informasi</h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Tool akan otomatis terkait dengan project: <strong>{{ $project->title_project }}</strong></li>
                                            <li>Bidang diperlukan untuk kategorisasi dan tracking</li>
                                            <li>Document assignment bersifat optional - bisa dilakukan setelah tool dibuat</li>
                                            <li>Semua field bertanda * wajib diisi</li>
                                            @php
                                                $boqCount = \App\Models\Detail::whereHas('section.boq', function($query) use ($project) {
                                                    $query->where('project_no_io', $project->no_IO);
                                                })->count();
                                            @endphp
                                            <li class="font-medium {{ $boqCount > 0 ? 'text-green-800' : 'text-yellow-800' }}">
                                                BOQ Status: {{ $boqCount > 0 ? "$boqCount item tersedia" : "Belum ada BOQ data" }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($boqCount > 0)
                            <!-- BOQ Sample Items -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-blue-800 mb-2">💡 Contoh Item BOQ Tersedia:</h4>
                                <div class="text-xs text-blue-700 space-y-1">
                                    @php
                                        $sampleDetails = \App\Models\Detail::whereHas('section.boq', function($query) use ($project) {
                                            $query->where('project_no_io', $project->no_IO);
                                        })->limit(5)->get();
                                    @endphp
                                    @foreach($sampleDetails as $detail)
                                        <div class="flex justify-between items-center p-2 bg-white rounded border">
                                            <span><strong>{{ $detail->nama_detail }}</strong> ({{ $detail->quantity }} {{ $detail->unit }})</span>
                                            <span class="text-green-600">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end gap-4">
                            <a href="{{ route('projects.tools.index', $project) }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition-colors duration-200 text-center">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-2 rounded-md shadow-md transform hover:scale-105 transition-all duration-200">
                                🔧 Tambah Tool
                            </button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>

    <script>
        function fixToolData() {
            // Scroll to the form
            document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
            
            // Focus on the description field
            document.getElementById('Description').focus();
            
            // Add visual indicator
            document.getElementById('Description').classList.add('ring-2', 'ring-blue-500');
            setTimeout(() => {
                document.getElementById('Description').classList.remove('ring-2', 'ring-blue-500');
            }, 3000);
        }



        // Real-time validation feedback
        document.addEventListener('DOMContentLoaded', function() {
            const descriptionInput = document.getElementById('Description');
            const quantityInput = document.getElementById('quantity');
            const unitInput = document.getElementById('unit');
            
            let validationTimeout;
            
            function performRealTimeValidation() {
                clearTimeout(validationTimeout);
                validationTimeout = setTimeout(() => {
                    const description = descriptionInput.value.trim();
                    const quantity = quantityInput.value;
                    const unit = unitInput.value.trim();
                    
                    if (description && quantity && unit && quantity > 0) {
                        showValidationFeedback('checking');
                        validateWithServer(description, quantity, unit);
                    } else {
                        hideValidationFeedback();
                    }
                }, 1500);
            }
            
            function validateWithServer(description, quantity, unit) {
                fetch('{{ route("projects.tools.validate-boq", $project) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        description: description,
                        quantity: parseFloat(quantity),
                        unit: unit
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.is_valid) {
                        showValidationFeedback('success', data);
                    } else {
                        showValidationFeedback('error', data);
                    }
                })
                .catch(error => {
                    console.error('Validation error:', error);
                    showValidationFeedback('network_error');
                });
            }
            
            function showValidationFeedback(status, data = null) {
                hideValidationFeedback();
                
                const feedback = document.createElement('div');
                feedback.id = 'realtime-validation';
                
                switch (status) {
                    case 'checking':
                        feedback.className = 'bg-blue-50 border border-blue-200 rounded-lg p-3 mt-4';
                        feedback.innerHTML = `
                            <div class="flex items-center">
                                <svg class="animate-spin w-4 h-4 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-blue-700 text-sm">🔍 Mengecek ketersediaan di BOQ...</span>
                            </div>
                        `;
                        break;
                        
                    case 'success':
                        feedback.className = 'bg-green-50 border border-green-200 rounded-lg p-3 mt-4';
                        let successContent = `
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-green-700 text-sm font-medium">Item tersedia di BOQ!</span>
                            </div>
                        `;
                        
                        // Show matched details
                        if (data.matched_details && data.matched_details.length > 0) {
                            if (data.matched_details.length === 1) {
                                // Single match - show directly
                                const detail = data.matched_details[0];
                                if (detail.availability) {
                                    const avail = detail.availability;
                                    successContent += `
                                        <div class="bg-white rounded border border-green-100 p-3 text-xs space-y-1">
                                            <div class="flex justify-between items-center">
                                                <span class="font-medium text-green-800">${avail.item_name}</span>
                                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Available</span>
                                            </div>
                                            <div class="text-gray-600 space-y-1">
                                                <p><span class="text-gray-500">Section:</span> ${avail.section_name}</p>
                                                <p><span class="text-gray-500">BOQ:</span> ${avail.boq_number}</p>
                                                <div class="flex justify-between">
                                                    <span>Stock: ${avail.remaining_quantity}/${avail.boq_quantity} ${avail.unit}</span>
                                                    <span class="font-medium">Rp ${avail.boq_unit_price ? avail.boq_unit_price.toLocaleString('id-ID') : 0}/${avail.unit}</span>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                }
                            } else {
                                // Multiple matches - show dropdown
                                successContent += `
                                    <div class="bg-white rounded border border-green-100 p-3 text-xs space-y-2">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-green-800">Multiple items found (${data.matched_details.length})</span>
                                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">Select One</span>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="block text-gray-700 text-xs font-medium mb-1">Choose item:</label>
                                            <select id="boq-item-select" class="w-full border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:border-green-500" onchange="updateSelectedBoqItem(this.value)">
                                                ${data.matched_details.map((detail, index) => {
                                                    const avail = detail.availability;
                                                    return `<option value="${index}">${avail.item_name} - ${avail.section_name} (Stock: ${avail.remaining_quantity}/${avail.boq_quantity} ${avail.unit}) - Rp ${avail.boq_unit_price ? avail.boq_unit_price.toLocaleString('id-ID') : 0}</option>`;
                                                }).join('')}
                                            </select>
                                        </div>
                                        <div id="selected-boq-info">
                                            ${(() => {
                                                const avail = data.matched_details[0].availability;
                                                return `
                                                    <div class="bg-gray-50 rounded p-2 space-y-1">
                                                        <p><span class="text-gray-500">Section:</span> ${avail.section_name}</p>
                                                        <p><span class="text-gray-500">BOQ:</span> ${avail.boq_number}</p>
                                                        <div class="flex justify-between">
                                                            <span>Stock: ${avail.remaining_quantity}/${avail.boq_quantity} ${avail.unit}</span>
                                                            <span class="font-medium">Rp ${avail.boq_unit_price ? avail.boq_unit_price.toLocaleString('id-ID') : 0}/${avail.unit}</span>
                                                        </div>
                                                    </div>
                                                `;
                                            })()}
                                        </div>
                                    </div>
                                `;
                                
                                // Store matched details globally for the dropdown function
                                window.currentMatchedDetails = data.matched_details;
                            }
                        }
                        
                        feedback.innerHTML = successContent;
                        break;
                        
                    case 'error':
                        feedback.className = 'bg-red-50 border border-red-200 rounded-lg p-3 mt-4';
                        let errorContent = `
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <span class="text-red-700 text-sm font-medium">Item tidak ditemukan di BOQ</span>
                            </div>
                        `;
                        
                        if (data.suggestions && data.suggestions.length > 0) {
                            errorContent += `
                                <div class="mt-3">
                                    <p class="text-red-700 text-xs font-medium mb-2">💡 Item serupa yang tersedia:</p>
                                    <div class="space-y-2">
                            `;
                            data.suggestions.slice(0, 3).forEach(suggestion => {
                                const detail = suggestion.detail;
                                errorContent += `
                                    <div class="bg-white border border-red-100 rounded p-2 text-xs">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-gray-800">${detail.nama_detail}</span>
                                            <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs">${(suggestion.similarity * 100).toFixed(0)}% match</span>
                                        </div>
                                        <div class="text-gray-600 mt-1 space-y-1">
                                            <div><span class="text-gray-500">Section:</span> ${detail.section_name}</div>
                                            <div class="flex justify-between">
                                                <span>Stock: ${detail.quantity} ${detail.unit}</span>
                                                <span class="font-medium">Rp ${detail.harga_satuan ? detail.harga_satuan.toLocaleString('id-ID') : 0}/${detail.unit}</span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            errorContent += '</div></div>';
                        }
                        

                        
                        feedback.innerHTML = errorContent;
                        break;
                        
                    case 'network_error':
                        feedback.className = 'bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-4';
                        feedback.innerHTML = `
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-yellow-700 text-sm">⚠️ Tidak dapat memvalidasi dengan BOQ saat ini</span>
                            </div>
                        `;
                        break;
                }
                
                // Insert after the unit input row
                const unitRow = unitInput.closest('.grid');
                unitRow.insertAdjacentElement('afterend', feedback);
            }
            
            function hideValidationFeedback() {
                const existingFeedback = document.getElementById('realtime-validation');
                if (existingFeedback) {
                    existingFeedback.remove();
                }
            }
            
            // Add event listeners
            [descriptionInput, quantityInput, unitInput].forEach(input => {
                input.addEventListener('input', performRealTimeValidation);
            });
        });

        // Function to handle BOQ item dropdown selection
        function updateSelectedBoqItem(selectedIndex) {
            if (!window.currentMatchedDetails || !window.currentMatchedDetails[selectedIndex]) {
                return;
            }
            
            const selectedDetail = window.currentMatchedDetails[selectedIndex];
            const avail = selectedDetail.availability;
            
            const infoDiv = document.getElementById('selected-boq-info');
            if (infoDiv) {
                infoDiv.innerHTML = `
                    <div class="bg-gray-50 rounded p-2 space-y-1">
                        <p><span class="text-gray-500">Section:</span> ${avail.section_name}</p>
                        <p><span class="text-gray-500">BOQ:</span> ${avail.boq_number}</p>
                        <div class="flex justify-between">
                            <span>Stock: ${avail.remaining_quantity}/${avail.boq_quantity} ${avail.unit}</span>
                            <span class="font-medium">Rp ${avail.boq_unit_price ? avail.boq_unit_price.toLocaleString('id-ID') : 0}/${avail.unit}</span>
                        </div>
                    </div>
                `;
            }
        }

    </script>
</x-app-layout>