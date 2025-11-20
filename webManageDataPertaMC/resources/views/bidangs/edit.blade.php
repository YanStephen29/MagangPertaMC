<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            {{ __('Edit Bidang') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="w-full px-3 sm:px-6 lg:px-8 max-w-2xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <form action="{{ route('bidangs.update', $bidang) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="kode_GL" class="block text-sm font-medium text-gray-700 mb-2">
                                Kode GL *
                            </label>
                            <input type="text" 
                                   name="kode_GL" 
                                   id="kode_GL" 
                                   value="{{ old('kode_GL', $bidang->kode_GL) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                   maxlength="20"
                                   required>
                        </div>

                        <div class="mb-6">
                            <label for="nama_Bidang" class="block text-sm font-medium text-gray-700 mb-2">
                                GL Name *
                            </label>
                            <input type="text" 
                                   name="nama_Bidang" 
                                   id="nama_Bidang" 
                                   value="{{ old('nama_Bidang', $bidang->nama_Bidang) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                   maxlength="55"
                                   required>
                        </div>

                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0">
                            <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300 w-full sm:w-auto">
                                Update GL
                            </button>
                            <a href="{{ route('bidangs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-colors duration-200 w-full sm:w-auto text-center">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
