<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    Edit Request : {{ $tool->Description }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Title Project : {{ $project->title_project }} ({{ $project->no_IO }})</p>
            </div>
            <div class="mb-4">
                <a href="{{ route('projects.tools.index', $project) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Request
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

                    <form action="{{ route('projects.tools.update', [$project, $tool]) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Description -->
                        <div>
                            <label for="Description" class="block text-sm font-medium text-gray-700 mb-2">
                                🔧 Deskripsi Request <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="Description" 
                                   id="Description" 
                                   value="{{ old('Description', $tool->Description) }}"
                                   placeholder="Contoh: Hydraulic Pump, Steel Pipe, Safety Valve, dll"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('Description') border-red-500 @enderror"
                                   maxlength="45"
                                   required>
                            @error('Description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity and Unit Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    📊 Quantity <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity" 
                                       value="{{ old('quantity', $tool->quantity) }}"
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
                                       value="{{ old('unit', $tool->unit) }}"
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
                                   value="{{ old('delivery_date', $tool->delivery_date ? $tool->delivery_date->format('Y-m-d') : '') }}"
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
                                    <option value="{{ $bidang->kode_GL }}" {{ old('kode_GL', $tool->kode_GL) == $bidang->kode_GL ? 'selected' : '' }}>
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
                                    <option value="{{ $doc->no_request }}" {{ old('no_document', $tool->no_document) == $doc->no_request ? 'selected' : '' }}>
                                        {{ $doc->no_request }} - {{ $doc->jenis_request }} ({{ $doc->date_issue->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('no_document')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-600 mt-1">Optional - pilih document untuk mengaitkan request ini</p>
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
                                      maxlength="45">{{ old('remarks', $tool->remarks) }}</textarea>
                            @error('remarks')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-600 mt-1">Maksimal 45 karakter</p>
                        </div>

                        <!-- Current Assignment Status -->
                        @if($tool->document)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Status Saat Ini</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <p>Request di-assign ke document: <strong>{{ $tool->document->no_request }}</strong></p>
                                            <p>Jenis Request: <strong>{{ $tool->document->jenis_request }}</strong></p>
                                            <p>Tanggal Issue: <strong>{{ $tool->document->date_issue->format('d/m/Y') }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

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
                                            <li>Request ID: <strong>{{ $tool->idTools }}</strong></li>
                                            <li>Terkait dengan project: <strong>{{ $project->title_project }}</strong></li>
                                            <li>Perubahan document assignment akan mempengaruhi laporan dan tracking</li>
                                            <li>Semua field bertanda * wajib diisi</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end gap-4">
                            <a href="{{ route('projects.tools.index', $project) }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition-colors duration-200 text-center">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-2 rounded-md shadow-md transform hover:scale-105 transition-all duration-200">
                                Update Tool
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                <h4 class="font-medium text-gray-700 mb-3">Quick Actions</h4>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('projects.tools.create', $project) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm inline-flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Tool Baru
                    </a>
                    <a href="{{ route('documents.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm inline-flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Buat Document Baru
                    </a>
                    @if(auth()->guard('admin')->check() && in_array(auth()->guard('admin')->user()->role, ['Admin', 'Project Manager']))
                        <a href="{{ route('projects.boq.index', $project) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm inline-flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            💰 Kelola BOQ
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>