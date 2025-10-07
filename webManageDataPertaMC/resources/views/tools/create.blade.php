<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                Tambah Tool Baru
            </h2>
            <p class="text-sm text-gray-600 mt-1">Project: {{ $project->title_project }} ({{ $project->no_IO }})</p>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="w-full px-3 sm:px-6 lg:px-8 max-w-2xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <form action="{{ route('projects.tools.store', $project) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="Description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description *
                            </label>
                            <input type="text" 
                                   name="Description" 
                                   id="Description" 
                                   value="{{ old('Description') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                   maxlength="45"
                                   required>
                            @error('Description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Quantity *
                                </label>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity" 
                                       value="{{ old('quantity') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                       min="1"
                                       required>
                                @error('quantity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                                    Unit *
                                </label>
                                <input type="text" 
                                       name="unit" 
                                       id="unit" 
                                       value="{{ old('unit') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                       maxlength="20"
                                       placeholder="pcs, kg, liter, etc."
                                       required>
                                @error('unit')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Delivery Date
                            </label>
                            <input type="date" 
                                   name="delivery_date" 
                                   id="delivery_date" 
                                   value="{{ old('delivery_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            @error('delivery_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Opsional - kosongkan jika belum ada tanggal delivery</p>
                        </div>

                        <div class="mb-4">
                            <label for="kode_GL" class="block text-sm font-medium text-gray-700 mb-2">
                                Bidang *
                            </label>
                            <select name="kode_GL" 
                                    id="kode_GL" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                    required>
                                <option value="">Pilih Bidang</option>
                                @foreach($bidangs as $bidang)
                                    <option value="{{ $bidang->kode_GL }}" {{ old('kode_GL') == $bidang->kode_GL ? 'selected' : '' }}>
                                        {{ $bidang->kode_GL }} - {{ $bidang->nama_Bidang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_GL')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">
                                Remarks
                            </label>
                            <input type="text" 
                                   name="remarks" 
                                   id="remarks" 
                                   value="{{ old('remarks') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                   maxlength="45"
                                   placeholder="Catatan tambahan (opsional)">
                            @error('remarks')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0">
                            <button type="submit" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-red-300 w-full sm:w-auto">
                                🔧 Simpan Tool
                            </button>
                            <a href="{{ route('projects.tools.index', $project) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-colors duration-200 w-full sm:w-auto text-center">
                                ↩️ Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
