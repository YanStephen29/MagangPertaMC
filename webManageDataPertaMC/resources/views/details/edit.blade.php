<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    ✏️ Edit Detail
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Detail: {{ $detail->nama_detail }} | Section: {{ $section->nama }} | {{ $project->title_project }}
                </p>
            </div>
            <div class="mb-4">
                <a href="{{ route('sections.details.index', [$project, $section]) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Details
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-6">


                    @if(session('step') == 2)
                        <!-- Step Indicator for Step 2 -->
                        <div class="mb-8">
                            <div class="flex items-center justify-center">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-sm font-medium">✓</div>
                                    <span class="ml-2 text-sm font-medium text-green-600">Basic Information</span>
                                </div>
                                <div class="flex-1 mx-4 h-0.5 bg-blue-600"></div>
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-medium">2</div>
                                    <span class="ml-2 text-sm font-medium text-blue-600">Detail Specification</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('sections.details.update', [$project, $section, $detail]) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="step" value="{{ session('step', 'edit') }}">
                        <input type="hidden" name="action_type" id="actionType" value="save">

                        @if(session('step') != 2)
                            <!-- Basic Information (can be edited) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Detail Name -->
                                <div>
                                    <label for="nama_detail" class="block text-sm font-medium text-gray-700 mb-2">
                                        📝 Name Detail <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="nama_detail" 
                                           id="nama_detail" 
                                           value="{{ old('nama_detail', $detail->nama_detail) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                           maxlength="45"
                                           required>
                                    @error('nama_detail')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Parent Selection -->
                                <div>
                                    <label for="parent_no" class="block text-sm font-medium text-gray-700 mb-2">
                                        🔗 Parent Detail
                                    </label>
                                    <select name="parent_no" 
                                            id="parent_no" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                        <option value="">-- There's no parent --</option>
                                        @foreach($potentialParents as $parent)
                                            <option value="{{ $parent->no }}" {{ old('parent_no', $detail->parent_no) == $parent->no ? 'selected' : '' }}>
                                                {{ $parent->nama_detail }} (ID: {{ $parent->no }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @else
                            <!-- Show basic info as readonly in step 2 but still send the data -->
                            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                                <h3 class="font-medium text-gray-700 mb-2">Basic Information</h3>
                                <p><strong>Name Detail:</strong> {{ $detail->nama_detail }}</p>
                                @if($detail->parent)
                                    <p><strong>Parent:</strong> {{ $detail->parent->nama_detail }}</p>
                                @endif
                            </div>
                            
                            <!-- Hidden fields to send basic info data -->
                            <input type="hidden" name="nama_detail" value="{{ $detail->nama_detail }}">
                            <input type="hidden" name="parent_no" value="{{ $detail->parent_no }}">
                        @endif



                        <!-- Quantity and Unit -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    📊 Quantity <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity" 
                                       value="{{ old('quantity', $detail->quantity) }}"
                                       min="1"
                                       step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                       required>
                                @error('quantity')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                                    📏 Unit <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="unit" 
                                       id="unit" 
                                       value="{{ old('unit', $detail->unit) }}"
                                       placeholder="pcs, m, kg, dll"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                       maxlength="5"
                                       required>
                                @error('unit')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>



                        <!-- Price -->
                        <div>
                            <label for="harga_satuan" class="block text-sm font-medium text-gray-700 mb-2">
                                💰 Price/Unit <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" 
                                       name="harga_satuan" 
                                       id="harga_satuan" 
                                       value="{{ old('harga_satuan', $detail->harga_satuan) }}"
                                       min="0"
                                       class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                       required>
                            </div>
                            @error('harga_satuan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-600 mt-1">Total Price will be calculated automatically: Quantity × Price/Unit</p>
                        </div>

                        <!-- Note -->
                        <div>
                            <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                                📝 Note <span class="text-gray-500">(Optional)</span>
                            </label>
                            <textarea name="note" 
                                      id="note" 
                                      rows="4"
                                      placeholder="Add notes or additional information for this detail..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 resize-vertical"
                                      maxlength="1000">{{ old('note', $detail->note) }}</textarea>
                            @error('note')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-600 mt-1">Maximal 1000 characters</p>
                        </div>

                        <!-- Current Total Display -->
                        @if($detail->harga_total > 0)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h4 class="font-medium text-green-800 mb-2">💰 Current Total Price</h4>
                                <p class="text-2xl font-bold text-green-600">{{ $detail->formatted_harga_total }}</p>
                                <p class="text-sm text-green-600">{{ $detail->quantity }} {{ $detail->unit }} × Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</p>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-between gap-4">
                            <a href="{{ route('sections.details.index', [$project, $section]) }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition-colors duration-200 text-center">
                                Cancel
                            </a>
                            
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button type="submit" 
                                        onclick="document.getElementById('actionType').value='save'"
                                        class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-2 rounded-md shadow-md transform hover:scale-105 transition-all duration-200 inline-flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Save
                                </button>
                                
                                @if(session('step') == 2)
                                    <a href="{{ route('sections.details.create', [$project, $section]) }}" 
                                       class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-2 rounded-md shadow-md transform hover:scale-105 transition-all duration-200 inline-flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Add Another Detail
                                    </a>
                                    <a href="{{ route('projects.boq.sections.create', [$project, $section->boq]) }}" 
                                       class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-2 rounded-md shadow-md transform hover:scale-105 transition-all duration-200 inline-flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        Add Section
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto calculate total when quantity or price changes
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const priceInput = document.getElementById('harga_satuan');
            
            function updateTotal() {
                const quantity = parseFloat(quantityInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = quantity * price;
                
                // You could add a preview total display here if needed
                console.log('Total will be: Rp', new Intl.NumberFormat('id-ID').format(total));
            }
            
            quantityInput.addEventListener('input', updateTotal);
            priceInput.addEventListener('input', updateTotal);
        });
    </script>
</x-app-layout>