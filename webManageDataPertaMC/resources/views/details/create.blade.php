<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    🔧 Tambah Detail Baru
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Section: {{ $section->nama }} | BOQ: {{ $section->boq->nomorBoq }} | {{ $project->title_project }}
                </p>
            </div>
            <div class="mb-4">
                <a href="{{ route('sections.details.index', [$project, $section]) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Details
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

                    <form action="{{ route('sections.details.store', [$project, $section]) }}" method="POST" class="space-y-6" id="detailForm">
                        @csrf

                        <!-- Detail ID (Auto-increment) -->
                        <div>
                            <label for="detail_no" class="block text-sm font-medium text-gray-700 mb-2">
                                🔢 Detail No.
                            </label>
                            <input type="text" 
                                   name="detail_no" 
                                   id="detail_no" 
                                   value="{{ $section->details()->count() + 1 }}"
                                   readonly
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-600 cursor-not-allowed">
                            <p class="text-sm text-gray-600 mt-1">Nomor urut detail dalam section ini</p>
                        </div>

                        <!-- Detail Name -->
                        <div>
                            <label for="nama_detail" class="block text-sm font-medium text-gray-700 mb-2">
                                📝 Nama Detail <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nama_detail" 
                                   id="nama_detail" 
                                   value="{{ old('nama_detail') }}"
                                   placeholder="Contoh: Steel Beam, Concrete Mix, Labor Cost, dll"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                   maxlength="45"
                                   required>
                            @error('nama_detail')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-600 mt-1">Maksimal 45 karakter</p>
                        </div>

                        <!-- Parent Selection -->
                        <div>
                            <label for="parent_no" class="block text-sm font-medium text-gray-700 mb-2">
                                🔗 Parent Detail <span class="text-gray-500">(Optional)</span>
                            </label>
                            <select name="parent_no" 
                                    id="parent_no" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                <option value="">-- Tidak ada parent (Root Level) --</option>
                                @foreach($potentialParents as $parent)
                                    <option value="{{ $parent->no }}" {{ old('parent_no', $parentDetail?->no) == $parent->no ? 'selected' : '' }}>
                                        {{ $parent->nama_detail }} (ID: {{ $parent->no }})
                                        @if($parent->parent)
                                            - Sub dari: {{ $parent->parent->nama_detail }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_no')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-600 mt-1">Pilih parent jika ini adalah sub-detail dari item lain</p>
                        </div>

                        <!-- Quantity and Unit -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    📊 Quantity <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity" 
                                       value="{{ old('quantity', 1) }}"
                                       min="0.01"
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
                                       value="{{ old('unit', 'pcs') }}"
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
                                💰 Harga Satuan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" 
                                       name="harga_satuan" 
                                       id="harga_satuan" 
                                       value="{{ old('harga_satuan', 0) }}"
                                       min="0"
                                       class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                       required>
                            </div>
                            @error('harga_satuan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-600 mt-1">Harga total akan dihitung otomatis: Quantity × Harga Satuan</p>
                        </div>

                        <!-- Note -->
                        <div>
                            <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                                📝 Catatan <span class="text-gray-500">(Optional)</span>
                            </label>
                            <textarea name="note" 
                                      id="note" 
                                      rows="3"
                                      placeholder="Tambahkan catatan atau keterangan tambahan untuk detail ini..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 resize-vertical"
                                      maxlength="1000">{{ old('note') }}</textarea>
                            @error('note')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-600 mt-1">Maksimal 1000 karakter</p>
                        </div>

                        <!-- Information Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Detail akan ditambahkan ke section: <strong>{{ $section->nama }}</strong></li>
                                            <li>Isi semua informasi detail termasuk quantity, unit, dan harga</li>
                                            <li>Catatan bersifat opsional untuk informasi tambahan</li>
                                            @if($parentDetail)
                                                <li>Detail ini akan menjadi sub-item dari: <strong>{{ $parentDetail->nama_detail }}</strong></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-between gap-4">
                            <a href="{{ route('sections.details.index', [$project, $section]) }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition-colors duration-200 text-center">
                                Batal
                            </a>
                            
                            <button type="submit" 
                                    class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-2 rounded-md shadow-md transform hover:scale-105 transition-all duration-200 inline-flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Simpan Detail
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>